<?php
namespace App\Services\Order;

use App\CodeResponse;
use App\Constant;
use App\Exceptions\BusinessException;
use App\Inputs\OrderSubmitInput;

use App\Jobs\OrderUnpaidTime;
use App\Models\Collect;
use App\Models\Goods\GoodsProduct;
use App\Models\Order\Cart;
use App\Models\Order\Order;

use App\Models\Order\OrderGoods;
use App\Notifications\NewPaidOrderEmailNotify;
use App\Notifications\NewPaidOrderSMSNotify;
use App\Services\BaseServices;
use App\Services\Goods\GoodsServices;
use App\Services\Promotion\CouponServices;
use App\Services\Promotion\GrouponServices;
use App\Services\SystemServices;
use App\Services\User\AddressServices;
use App\Services\User\UserServices;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;


class OrderServices extends BaseServices
{
    /**
     * @param $userId
     * @param OrderSubmitInput $input
     * @return mixed
     */

    public function submit($userId,OrderSubmitInput $input){
        //验证团购规则有效性
        if (!empty($input->grouponRulesId)){
            GrouponServices::getInstance()->checkGrouponVaild($userId,$input->grouponRulesId);
        }
        $address=AddressServices::getInstance()->getAddress($userId,$input->addressId);
        if (!empty($address)){
            return $this->throwBusinessException(CodeResponse::PARAM_ILLEGAL);
        }
        //获取购物车的商品列表
        $checkedGoodsList=CartServices::getInstance()->getCheckedCartList($userId,$input->cartId);
        //计算商品总金额
        $grouponPrice=0;
        $checkedGoodsPrice=CartServices::getInstance()->getCartPriceCutGroupon($checkedGoodsList,$input->grouponRulesId,$grouponPrice);
        //获取优惠卷面额
        $countPrice=0;
        if ($input->couponId>0){
            $coupon=CouponServices::getInstance()->getCoupon($input->couponId);
            $couponUser=CouponServices::getInstance()->getCouponUser($input->userCouponId);
            $is=CouponServices::getInstance()->checkCouponAndPrice($coupon,$couponUser,$checkedGoodsPrice);
            if($is){
                $couponPrice=$coupon->discount;
            }
        }
        $freightPrice=$this->getFreight($checkedGoodsPrice);
        $orderTotalPrice=bcadd($checkedGoodsPrice,$freightPrice);
        $orderTotalPrice=bcsub($orderTotalPrice,$couponPrice);
        $orderTotalPrice=max(0,$orderTotalPrice);

        $order=Order::new();
        $order->user_id=$userId;
        $order->order_sn=$this->genrateOrderSn();
        $order->order_status=101;
        $order->consignee=$address->name;
        $order->mobile=$address->tel;
        $order->address=$address->province.$address->city.$address->county." ".$address->address_detail;
        $order->message=$input->message;
        $order->goodsPrice=$checkedGoodsPrice;
        $order->freight_price=$freightPrice;
        $order->coupon_price=$couponPrice;
        $order->order_price=$orderTotalPrice;
        $order->actual->price=$orderTotalPrice;
        $order->groupon_price=$grouponPrice;
        $order->save();
        //写入订单商品记录
        $this->saveOrderGoods($checkedGoodsList,$order->id);
        //清理购物车记录
        CartServices::getInstance()->clearCartGoods($userId,$input->cartId);
        //减库存
        $this->reduceProductStock($checkedGoodsList);
        //添加团购记录
        GrouponServices::getInstance()->openOrjoinGroupon($userId,$order->id,$input->grouponRulesId,$input->grouponLinkId);
        //设置超时任务
        dispatch(new OrderUnpaidTime($userId,$order->id));

        return $order;

    }

    /**减库存
     * @param Cart[]|Collection $goodsList
     */
    public function reduceProductStock($goodsList){
       $productIds=$goodsList->pluck('product_id')->toArray();
       $products=GoodsServices::getInstance()->getGoodsProductsByids($productIds)->keyBy('id');
        foreach ($goodsList as $cart){
            $product=$products->get($cart->product_id);
            if(empty($product)){
                $this->throwBusinessException(CodeResponse::PARAM_ILLEGAL);
            }
            if ($product->number<$cart->number){
                $this->throwBusinessException(CodeResponse::GOODS_NO_STOCK);
            }
            $row=GoodsServices::getInstance()->reduceStock($product->id,$cart->number);
            if($row==0){
                $this->throwBusinessException(CodeResponse::GOODS_NO_STOCK);
            }

        }

    }
    /**
     * @param $checkGoodsList
     * @param $orderId
     * @return void
     */
    private function saveOrderGoods($checkGoodsList,$orderId)
    {
        foreach ($checkGoodsList as $cart){
            $orderGoods=Order::new();
            $orderGoods->order_id=$orderId;
            $orderGoods->goods_id=$cart->goods_id;
            $orderGoods->goods_sn=$cart->goods_sn;
            $orderGoods->product_id=$cart->product_id;
            $orderGoods->goods_name=$cart->goods_name;
            $orderGoods->pic_url=$cart->pic_url;
            $orderGoods->price=$cart->price;
            $orderGoods->number=$cart->number;
            $orderGoods->specifications=$cart->specifications;
            $orderGoods->save();
        }
    }


    /**
     * @return mixed
     * @throws \App\Exceptions\BusinessException
     */
    public function genrateOrderSn(){
        return retry(5,function (){
            $orderSn=date('YmdHis').Str::random(6);
            if(!$this->isOrderSnUsed($orderSn)){
                return $orderSn;
            }
            Log::warning('订单号获取失败, orderSn'.$orderSn);
            $this->throwBusinessException(CodeResponse::FAIL,'订单号获取失败');
        });

    }
    public function isOrderSnUsed($orderSn){
        return Order::query()->where('order_sn',$orderSn)->exists();
    }

    //获取运费
    /**
     * @param $price
     * @return float|int
     */
    public function getFreight($price){
        $freightPrice=0;
        $freightMin=SystemServices::getInstance()->getFreightMin();
        if(bccomp($freightMin,$price)==1){
            $freightPrice=SystemServices::getInstance()->getFreightValue();
        }
        return $freightPrice;

    }

    public function getOrderByUserIdAndId($userId,$orderId){
    return Order::query()->where('user_id',$userId)->find($orderId);
}

    public function getOrderGoodsList($orderId){
        return OrderGoods::query()->where('order_id',$orderId)->get();
    }

    public function userCancel($userId,$orderId)
    {
        DB::transaction(function () use ($userId, $orderId) {
            $this->cancel($userId, $orderId, 'user');
        });
    }

    public function systemCancel($userId,$orderId) {
        DB::transaction(function () use ($userId, $orderId) {
            $this->cancel($userId, $orderId, 'system');
        });
    }

    public function adminCancel($userId,$orderId) {
        DB::transaction(function () use ($userId, $orderId) {
            $this->cancel($userId, $orderId, 'admin');
        });
    }
    /**取消订单
     * @param $userId
     * @param $orderId
     * @param $role //支持user admin system
     * @return bool
     * @throws \App\Exceptions\BusinessException
     */
    public function cancel($userId,$orderId,$role='user'){
        $order=$this->getOrderByUserIdAndId($userId,$orderId);
            if(is_null($order)){
                $this->throwBusinessException(CodeResponse::FAIL);
            }
            if($order->canCancelhandle()){
                $this->throwBusinessException(CodeResponse::ORDER_INVALID_OPERATION);
            }
            switch ($role){
                case'system':
                    $order->order_status=Constant::STATUS_AUTO_CANCEL;
                    break;
                case 'admin':
                    $order->order_status=Constant::STATUS_ADMIN_CANCEL;
                    break;
                default:
                    $order->order_status=Constant::STATUS_CANCEL;
            }
        Order::query()->where('update_time',$order->update_time)->where('id',$order->id)
            ->where('order_status',Constant::STATUS_CREATE)
            ->update(['order_status'=>Constant::STATUS_CANCEL]);

        $this->returnStock($orderId);
            return true;
        //if ($order->save()){
        //        $this->throwBusinessException(CodeResponse::UPDATED_FAIL);
        //    }
    }

    /**订单支付成功
     * @param Order $order
     * @param $payId
     * @return Order
     * @throws BusinessException
     */
    public function payOrder(Order $order,$payId){
        if(!$order->canPayHandle()){
            $this->throwBusinessException(CodeResponse::ORDER_PAY_FAIL,'订单不能支付');
        }
        $order->pay_id=$payId;
        $order->pay_time=now()->teDeateTimeString();
        $order->order_status=Constant::STATUS_PAY;
        if($order->cas()==0){
            $this->throwBusinessException(CodeResponse::UPDATED_FAIL);
        }
        GrouponServices::getInstance()->payGrouponOrder($order->id);
        Notification::route('mail','MAIL_USERNAME')->notify(new NewPaidOrderEmailNotify($order->id));
        $user=UserServices::getInstance()->getUserById($order->user_id);
        $user->notify(new NewPaidOrderSMSNotify());
        return $order;
    }

    public function ship($userId,$orderId,$shipSn,$shipChannel){
        $order=$this->getOrderByUserIdAndId($userId,$orderId);
        if (empty($order)){
            $this->throwBusinessException(CodeResponse::PARAM_ILLEGAL);
        }
        if (empty($order->canCancelHandle())){
            $this->throwBusinessException(CodeResponse::ORDER_INVALID_OPERATION,'该订单不能发货');
        }
        $order->order_status=Constant::STATUS_SHIP;
        $order->ship_sn=$shipSn;
        $order->ship_channel=$shipChannel;
        $order->ship_time=now()->toDateTimeString();
        if ($order->cas()==0){
            $this->throwBusinessException(CodeResponse::FAIL);
        }
        return $order;
    }

    public function refund($userId,$orderId){
        $order=$this->getOrderByUserIdAndId($userId,$orderId);
        if (empty($order)){
            $this->throwBusinessException(CodeResponse::PARAM_ILLEGAL);
        }
        if(!$order->canRefundHandle()){
            $this->throwBusinessException(CodeResponse::ORDER_INVALID_OPERATION,'该订单不能申请退款');
        }
        $order->order_status=Constant::STATUS_REFUND;
        if($order->cas()==0){
            $this->throwBusinessException(CodeResponse::PARAM_ILLEGAL);
        }
        //发通知
        return $order;

    }



    /**同意退款
     * @param Order $order
     * @param $refundType
     * @param $refundContent
     * @return Order
     * @throws \App\Exceptions\BusinessException
     */
    public function agreeRefund(Order $order,$refundType,$refundContent){
        if(!$order->canAgreeRefundHandle()){
            $this->throwBusinessException(CodeResponse::ORDER_INVALID_OPERATION,'该订单不能同意退款');
        }
        $now=now()->toDateTimeString();
        $order->order_status=Constant::STATUS_AUTO_CONFIRM;
        $order->end_time=$now;
        $order->refund_amount=$order->actual_price;
        $order->refund_type=$refundType;
        $order->refund_content=$refundContent;
        $order->refund_time=$now;
        if ($order->cas()==0){
            $this->throwBusinessException(CodeResponse::PARAM_ILLEGAL);
        }
        $this->returnStock($order->id);
        return $order;
    }

    /**确认收货
     * @param $userId
     * @param $orderId
     * @return \App\Models\BaseModel|\App\Models\BaseModel[]|Order|Order[]|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     * @throws \App\Exceptions\BusinessException
     */
    public function confirm($userId,$orderId,$isAuto=false)
    {
        $order = $this->getOrderByUserIdAndId($userId, $orderId);
        if (empty($order)) {
            $this->throwBusinessException(CodeResponse::UPDATED_FAIL);
        }
        if (!$order->canConfirmHandle()) {
            $this->throwBusinessException(CodeResponse::ORDER_INVALID_OPERATION,'该订单不能确认收货');
        }

        $order->comments=$this->countOrderGoods($orderId);
        $order->order_status=$isAuto ? Constant::STATUS_AUTO_CONFIRM : Constant::STATUS_CONFIRM;
        $order->confirm_time=now()->toDateTimeString();
        if($order->cas()==0){
            $this->throwBusinessException(CodeResponse::UPDATED_FAIL);
        }
        return $order;
    }

    public function delete($userId,$orderId){
        $order=$this->getOrderByUserIdAndId($userId,$orderId);
        if (empty($order)){
            $this->throwBusinessException(CodeResponse::PARAM_ILLEGAL);
        }
        if (!$order->canDeletedHandle()){
            $this->throwBusinessException(CodeResponse::ORDER_INVALID_OPERATION,'该订单不能删除');
        }
        $order->delete();
        //售后删除
    }

    public function getTimeoutUnConfirmOrders(){
        $days=SystemServices::getInstance()->getOrderUnConfirmDays();
        return Order::query()->where('order_status',Constant::STATUS_SHIP)
            ->where('ship_time','<=',now()->subDays($days))
            ->where('ship_time','>=',now()->subDays($days+30))
            ->get();
    }

    public function autoConfirm(){
        Log::info('Auto confirm start.');
        $orders=$this->getTimeoutUnConfirmOrders();
        foreach ($orders as $order){
            try {
                $this->confirm($order->user_id,$order->id,true);
            }catch (BusinessException $exception){
            }catch (\Throwable $exception){
                Log::error('Auto confirm error. Error'.$exception->getMessage());
            }
        }
    }

    public function countOrderGoods($orderId){
        return OrderGoods::whereOrderId($orderId)->count(['id']);
    }

    public function returnStock($orderId){
        $orderGoods=$this->getOrderGoodsList($orderId);
        foreach ($orderGoods as $goods){
            $row=GoodsServices::getInstance()->addStock($goods->product_id,$goods->number);
            if ($row==0){
                $this->throwBusinessException(CodeResponse::UPDATED_FAIL);
            }

        }
    }

    public function detail($userId,$orderId){
       $order=$this->getOrderByUserIdAndId($userId,$orderId);
       if (empty($order)){
           $this->throwBusinessException(CodeResponse::PARAM_ILLEGAL);
       }
       $detail=Arr::only($order->toArray(),[
           'id',
           'orderSn',
           'addTime',
           'message',
           'consignee',
           'mobile',
           'address',
           'goodsPrice',
           'couponPrice',
           'freightPrice',
           'actualPrice',
           'aftersaleStatus'
       ]) ;

       $detail['orderStatausText']=Constant::STATUS_TEXT_MAP[$order->order_status]??'';
       $detail['handleOption']=$order->getCanHandleOptions();
       $goodsList=$this->getOrderGoodsList($orderId);

       if($order->isShipStatus()){
           $detail['exCode']=$order->ship_channel;
           $detail['expNo']=$order->ship_sn;
           $detail['expName']=ExpressServices::getInstance()->getExpressName($order->ship_channel);
           $express=[];
       }

       return[
           'orderInfo'=>$detail,
           'orderGoods'=>$goodsList,
           'expressInfo'=>$express
       ];
    }
    public function getOrderBySn($orderSn){
        return Order::query()->where('order_sn',$orderSn)->first();
    }

    public function getWxPayOrder($userId, $orderId)
    {
        $order = $this->getOrderByUserIdAndId($userId, $orderId);
        if (empty($order)) {
            $this->throwBusinessException(CodeResponse::PARAM_ILLEGAL);
        }
        if (!$order->canPayHandle()) {
            $this->throwBusinessException(CodeResponse::ORDER_PAY_FAIL, '订单不能支付');
        }

        return [
            'out_trade_no' => $order->order_sn,
            'body' => '订单: ' . $order->order_sn,
            'total_fee' => bcmul($order->actual_price, 100) //微信支付的单位是分,所以要乘以100
        ];
    }


    public function wxNotify(array $data){
        $orderSn=$data['out_trade_no']??'';
        $payId=$data['transacetion_id']??'';
        $price=bcdiv($data['total_fee'],100,2);

        $order=$this->getOrderBySn($orderSn);
        if (is_null($order)){
            $this->throwBusinessException(CodeResponse::ORDER_UNKNOWN);
        }

        if($order->isHadPaid()){
            return $order;
        }

        if(bccomp($order->actual_price,$price,2!=0)){
            $this->throwBusinessException(CodeResponse::FAIL);
        }
        $this->payOrder($order,$payId);

    }


}



