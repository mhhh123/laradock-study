<?php
namespace App\Http\Controllers\Wx;

use App\CodeResponse;
use App\Inputs\OrderSubmitInput;
use App\Services\Order\OrderServices;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends WxController{

    public function submit(){
        $input=OrderSubmitInput::new();

        $lockkey=sprintf('order_submit_%s_%s',$this->userId(),md5(serialize($input)));
        $lock=Cache::lock($lockkey,5);
        if(!$lock->get()){
            return $this->fail(CodeResponse::FAIL,'请勿重复请求');
        }

        $order= DB::transaction(function ()use ($input){
            return  OrderServices::getInstance()->submit($this->userId(),$input);
        });

        return $this->success([
            'orderid'=>$order->id,
            'grouponLinkId'=>$input->grouponLinkId??0
        ]);
    }

    /**用户主动取消订单
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\BusinessException
     */
    public function cancel(){
        $orderId=$this->verifyId('orderId');
        OrderServices::getInstance()->userCancel($this->userId(),$orderId);
        return $this->success();
    }

    public function refund(){
        $orderId=$this->verifyId('orderId');
        OrderServices::getInstance()->refund($this->userId(),$orderId);
        return $this->success();
    }

    public function confirm(){
        $orderId=$this->verifyId('orderId');
        OrderServices::getInstance()->confirm($this->userId(),$orderId);
        return $this->success();
    }

    public function delete(){
        $orderId=$this->verifyId('orderId');
        OrderServices::getInstance()->delete($this->userId(),$orderId);
        return $this->success();
    }

    public function detail(){
        $orderId=$this->verifyId('orderId');
        $detail=OrderServices::getInstance()->detail($this->userId(),$orderId);
        return $this->success($detail);
    }


}
