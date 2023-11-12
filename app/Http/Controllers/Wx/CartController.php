<?php
namespace App\Http\Controllers\Wx;

use App\CodeResponse;

use App\Exceptions\BusinessException;

use App\Models\Promotion\Coupon;
use App\Models\Promotion\CouponUser;
use App\Services\Goods\GoodsServices;
use App\Services\Order\CartServices;
use App\Services\Order\OrderServices;
use App\Services\Promotion\CouponServices;
use App\Services\Promotion\GrouponServices;
use App\Services\SystemServices;
use App\Services\User\AddressServices;
use Illuminate\Http\JsonResponse;



class CartController extends WxController
{

    protected $only = [];
    /**
     * @return JsonResponse
     */
    public function index(){
        $list=CartServices::getInstance()->getVaildCartList($this->userId());
        //初始化
        $goodsCount=0;
        $goodsAmount=0;
        $checkedGoodsCount=0;
        $checkedGoodsAmount=0;


        foreach ($list as $item){
            $goodsCount+=$item->number;
            $goodsAmount+=$item->price*$item->number;
            $amount=bcmul($item->price,$item->number,2);
            $goodsAmount=bcadd($goodsAmount,$amount,2);
            if($item->checked){
                $checkedGoodsCount+=$item->number;
                $checkedGoodsAmount=bcadd($checkedGoodsAmount,$amount,2);
            }
        }
        return $this->success([
            'cartList'=>$list,
            'cartTotal'=>[
                'goodsCount'=>$goodsCount,
                'goodsAmount'=>$goodsAmount,
                'checkedGoodsCount'=>$checkedGoodsCount,
                'checkedGoodsAmount'=>$checkedGoodsAmount
            ]
        ]);
    }


    /**
     * @return JsonResponse
     * @throws BusinessException
     */
    public function fastadd(){
        $goodsId = $this->verifyId('goodsId', 0);
        $productId = $this->verifyId('productId', 0);
        $number = $this->verifyPositiveInteger('number', 0);
        $cart = CartServices::getInstance()->fastAdd($this->userId(), $goodsId, $productId, $number);
        return $this->success($cart->id);
    }

    /**加入购物车
     * @return JsonResponse
     * @throws BusinessException
     */
     public function add()
     {
         $goodsId = $this->verifyId('goodsId', 0);
         $productId = $this->verifyId('productId', 0);
         $number = $this->verifyPositiveInteger('number', 0);
         CartServices::getInstance()->add($this->userId(), $goodsId, $productId, $number);
         $count = CartServices::getInstance()->countCartProduct($this->userId());
         return $this->success($count);
     }

   public function goodsCount(){
       $count=CartServices::getInstance()->countCartProduct($this->userId());
       return $this->success($count);
   }

    /**更新购物车
     * @return JsonResponse
     * @throws BusinessException
     */
   public function update(){
        $id=$this->verifyId('id',0);
        $goodsId=$this->verifyId('goods_id',0);
        $productId=$this->verifyId('productId',0);
        $number=$this->verifyPositiveInteger('number',0);
        $cart=CartServices::getInstance()->getCartByid($this->userId(),$id);
        if(is_null($cart)){
            return $this->fail(CodeResponse::PARAM_ILLEGAL);
        }
        if ($cart->goods_id!=$goodsId||$cart->product_id!=$productId){
            return $this->fail(CodeResponse::PARAM_ILLEGAL);
        }
        $goods=GoodsServices::getInstance()->getGoods($goodsId);
        if (is_null($goods)||!$goods->is_on_sale){
            return $this->fail(CodeResponse::GOODS_NO_STOCK);
        }

        $product=GoodsServices::getInstance()->getGoodsProductByid($productId);
       if(is_null($product)||$product->number<$number){
           return  $this->fail(CodeResponse::GOODS_NO_STOCK);
       }
       $cart->number=$number;
       $ret=$cart->save();
       return $this->failorSuccess($ret);
   }

   public function delete(){
        $productIds=$this->verifyArrayNotEmpty('productIds',[]);
        CartServices::getInstance()->delete($this->userId(),$productIds);
        $list=CartServices::getInstance()->list($this->userId());
        return $this->success($list);
   }

   public function checked(){
       $productIds=$this->verifyArrayNotEmpty('productIds',[]);
       $isChecked=$this->verifyBoolean('isChecked');
       CartServices::getInstance()->updateChecked($this->userId(),$productIds,$isChecked==1);
       $list=CartServices::getInstance()->list($this->userId());
       return $this->success($list);
   }

   public function checkout(){
       $cartId=$this->verifyInteger('cartId');
       $addressId=$this->verifyInteger('addressId');
       $couponId=$this->verifyInteger('couponId');
       $userCouponId=$this->verifyInteger('userCouponId');
       $grouponRulesId=$this->verifyInteger('grouponRulesId');

       //获取地址
       $address=AddressServices::getInstance()->getAddressOrDefault($this->userId(),$addressId);
       $addressId=$address->id??0;

       //获取购物车商品列表
       $checkedGoodsList=CartServices::getInstance()->getCheckedCartList($this->userId(),$cartId);

       //计算订单总金额
       $grouponPrice=0;
       $checkedGoodsPrice= CartServices::getInstance()->getCartPriceCutGroupon($checkedGoodsList,$grouponRulesId,$grouponPrice);

       // 获取适合当前价格的优惠卷列表,并根据优惠折扣进行降序排序
       $availableCouponLenth=0;
       $couponUser=CouponServices::getInstance()->getMostMeetPriceCoupon($this->userId(),$couponId,$userCouponId,$checkedGoodsPrice,$availableCouponLenth);
       if(is_null($couponUser)){
           $couponId=-1;
           $userCouponId=-1;
           $couponPrice=0;
       }else{
           $couponId=$couponUser->coupon_id??0;
           $userCouponId=$couponUser->id??0;
           $couponPrice=CouponServices::getInstance()->getCoupon($couponId)->discount??0;
       }

       //运费
       $freightPrice=OrderServices::getInstance()->getFreight($checkedGoodsPrice);


       //计算订单金额
       $orderPrice=bcadd($checkedGoodsPrice,$freightPrice,2);
       $orderPrice=bcsub($orderPrice, $couponPrice,2);

       return $this->success([
           'addressId'=>$addressId ,
            'couponId'=> $couponId,
            'userCouponId'=> $userCouponId,
            'cartId'=> $cartId,
            'grouponRulesId'=>$grouponRulesId ,
            'grouponPrice'=> $grouponPrice,
            'checkedAddress'=> $address,
            'availableCouponLength'=> $availableCouponLenth,
            'goodsTotalPrice'=> $checkedGoodsPrice,
            'freightPrice'=> $freightPrice,
            'couponPrice'=> $couponPrice,
            'orderTotalPrice'=> $orderPrice,
            'actualPrice'=> $orderPrice,
            'checkedGoodsList'=> $checkedGoodsList
       ]);
   }

}
