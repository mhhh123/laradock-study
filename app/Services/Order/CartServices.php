<?php
namespace App\Services\Order;

use App\CodeResponse;

use App\Models\Goods\Goods;
use App\Models\Goods\GoodsProduct;
use App\Models\Order\Cart;
use App\Services\BaseServices;
use App\Services\Goods\GoodsServices;
use App\Services\Promotion\GrouponServices;

class CartServices extends BaseServices
{
    /**
     * @param $checkedGoodsList
     * @param $grouponRulesId
     * @param $grouponPrice
     * @return int|string
     */
    public function getCartPriceCutGroupon($checkedGoodsList,$grouponRulesId, &$grouponPrice=0){
        $grouponRules=GrouponServices::getInstance()->getGrouponRulesByIds($grouponRulesId);
        $checkedGoodsPrice=0;
        foreach ($checkedGoodsList as $cart){
            if($grouponRules &&$grouponRules->goods_id==$cart->goods_id){
                $grouponPrice=bcmul($grouponRules->discount,$cart->number,2);
                $price=bcsub($cart->price,$grouponRules->discount,2);
            }else{
                $price=$cart->price;
            }
            $price=bcmul($price,$cart->number,2);
            $checkedGoodsPrice=bcadd($checkedGoodsPrice,$price,2);
        }
        return $checkedGoodsPrice;
    }

    /**获取已选择购物车商品列表
     * @param $userId
     * @param $cartId
     * @return \App\Models\BaseModel[]|Cart[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|mixed
     * @throws \App\Exceptions\BusinessException
     */
    public function getCheckedCartList($userId,$cartId=null){
        if (empty($cartId)){
            $checkedGoodsList=$this->getCheckedCarts($userId);
        }else {
            $cart = $this->getCartByid($userId, $cartId);
            if (empty($cart)) {
                return $this->throwBusinessException(CodeResponse::PARAM_ILLEGAL);
            }
            $checkedGoodsList = collect([$cart]);
        }
        return $checkedGoodsList;
    }

    public function getCheckedCarts($userId){
        return Cart::query()->where('user_id',$userId)->where('checked',1)->get();
    }

    public function getCartList($userId){
        return Cart::query()->where('user_id',$userId)
            ->get();
    }

    public function getVaildCartList($userId){
        $list=$this->getCartList($userId);
        $goodsIds=$list->pluck('goods_id')->toArray();
        $goodsList=GoodsServices::getInstance()->getGoodslistByIds($goodsIds);
        $invaildCartIds=[];

        $list->filter( function (Cart $cart)use ($goodsList){
            $goods=$goodsList->get($cart->good_id);
            $isVaild=!empty($goods)&&$goods->is_on_sale;
            if(!$isVaild){
                $invaildCartIds[]=$cart->id;
            }
            return $isVaild;
        });

        $this->deleteCartList($invaildCartIds);
        return $list;

    }

    public function deleteCartList($ids){
        if(empty($ids)){
            return 0;
        }
        return Cart::query()->whereIn('id',$ids)->delete();
    }

    public function getCartByid($userId,$id,$columns=['*']){
        return Cart::query()->where('user_id',$userId)->where('id',$id)->first($columns);
    }


    public function getCartProduct($userId,$goodsId,$productId){
        return Cart::query()
            ->where('user_id',$userId)
            ->where('goods_id',$goodsId)
            ->where('product_id',$productId)
            ->first();
    }

    /**
     * @param $goodsId
     * @param $productId
     * @return array|mixed
     * @throws \App\Exceptions\BusinessException
     */
    public function getgoodsinfo($goodsId,$productId){
        $goods=GoodsServices::getInstance()->getGoods($goodsId);
        if (is_null($goods)||!$goods->is_on_sale){
            return $this->throwBusinessException(CodeResponse::GOODS_UNSHELVE);
        }

        $product=GoodsServices::getInstance()->getGoodsProductByid($productId);
        if(is_null($product)){
            return  $this->throwBusinessException(CodeResponse::GOODS_NO_STOCK);
        }
        return [$goods,$product];

    }

    /**
     * @param $userId
     * @param $goodsId
     * @param $productId
     * @param $number
     * @return Cart|mixed
     * @throws \App\Exceptions\BusinessException
     */
    public function add($userId, $goodsId, $productId, $number)
    {
        list($goods, $product) = $this->getGoodsInfo($goodsId, $productId);
        $cartProduct = $this->getCartProduct($userId, $goodsId, $productId);
        if (is_null($cartProduct)) {
            return $this->newCart($userId, $goods, $product, $number);
        } else {
            $number = $cartProduct->number + $number;
            return $this->editCart($cartProduct, $product, $number);
        }
    }

    /**
     * @param $userId
     * @param $goodsId
     * @param $productId
     * @param $number
     * @return Cart|mixed
     * @throws \App\Exceptions\BusinessException
     */
    public function fastadd($userId,$goodsId,$productId,$number){
        list($goods,$product)=$this->getgoodsinfo($goodsId,$productId);
        $cartProduct=$this->getCartProduct($userId,$goodsId,$productId);
        if (is_null($cartProduct)){
            return $this->newCart($userId,$goods,$product,$number);
        }
        else{
            return $this->editCart($cartProduct,$productId,$number);
        }
    }

    /**
     * @param $existCart
     * @param $product
     * @param $num
     * @return Cart
     * @throws \App\Exceptions\BusinessException
     */
    public function editCart($existCart,$product,$num){
        if($num>$product->number){
            return $this->throwBusinessException(CodeResponse::GOODS_NO_STOCK);
        }
        $existCart->number=$num;
        $existCart->save();
        return $existCart;
    }

    public function countCartProduct($userId){
        return Cart::query()
            ->where('user_id',$userId)
            ->sum('number');
    }

    public function newCart($userId,Goods $goods,GoodsProduct $product,$number){
        if ($number>$product->number){
            return $this->throwBusinessException(CodeResponse::GOODS_NO_STOCK);
        }
        $cart=Cart::new();
        $cart->goods_sn=$goods->goods_sn;
        $cart->goods_name=$goods->name;
        $cart->pic_url=$product->url?:$goods->pic_url;
        $cart->specifications=$product->specifications;
        $cart->user_id=$userId;
        $cart->checked=true;
        $cart->number=$number;
        $cart->goods_id=$goods->id;
        $cart->product_id=$goods->product->id;
        $cart->save();
        return $cart;
    }

    /**
     * @param $userId
     * @param $productIds
     * @return bool|mixed|null
     */
    public function delete($userId,$productIds){
        return Cart::query()->where('user_id',$userId)->whereIn('product_id',$productIds)
            ->delete();
    }

    public function updateChecked($userId,$productIds,$isChecked){
        return Cart::query()->where('user_id',$userId)
            ->whereIn('product_id',$productIds)
            ->update(['checked'=>$isChecked]);
    }

    public function clearCartGoods($userId,$cartId){
        if (empty($cartId)){
            return Cart::query()->where('user_id',$userId)->where('checked',1)->delete();
        }
        return Cart::query()->where('user_id',$userId)->where('id',$cartId)->delete();
    }

    /**
     * @param $userId
     * @return array
     */
    public function list($userId){
        return [];
    }

}



