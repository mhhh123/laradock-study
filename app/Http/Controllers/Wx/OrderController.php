<?php
namespace App\Http\Controllers\Wx;

use App\CodeResponse;
use App\Constant;
use App\Exceptions\BusinessException;
use App\Inputs\OrderSubmitInput;
use App\Inputs\PageInput;
use App\Models\Order\Order;
use App\Models\Order\OrderGoods;
use App\Services\Order\OrderServices;
use App\Services\Promotion\GrouponServices;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Ramsey\Collection\Collection;
use Yansongda\LaravelPay\Facades\Pay;

class OrderController extends WxController{


    public function submit()
    {
        $input = OrderSubmitInput::new();

        // 重复请求，幂等性
        $lockKey = sprintf('order_submit_%s_%s', $this->userId(), md5(serialize($input)));
        $lock = Cache::lock($lockKey, 5);
        if (!$lock->get()) {
            return $this->fail(CodeResponse::FAIL, '请勿重复请求');
        }

        $order = DB::transaction(function () use ($input) {
            return OrderServices::getInstance()->submit($this->userId(), $input);
        });

        return $this->success([
            'orderId' => $order->id,
            'grouponLikeId' => $input->grouponLinkId ?? 0
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

    /**
     * 订单详情
     * @return JsonResponse
     * @throws BusinessException
     */
    public function detail()
    {
        $orderId = $this->verifyId('orderId');
        $detail = OrderServices::getInstance()->detail($this->userId(), $orderId);
        return $this->success($detail);
    }


    public function list()
    {


        $showType = $this->verifyEnum('showType', 0, array_keys(Constant::SHOW_TYPE_STATUS_MAP));
        $filter = PageInput::new();
        $status = Constant::SHOW_TYPE_STATUS_MAP[$showType];
        $orderListWithPage = OrderServices::getInstance()->getListByStatus($this->userId(), $filter, $status);
        $orderList = collect($orderListWithPage->items());
        $orderIds = $orderList->pluck('id')->toArray();
        if (empty($orderIds)) {
            $this->successPaginate($orderListWithPage);
        }
        $grouponOrderIds = GrouponServices::getInstance()->getGrouponOrderInOrderIds($orderIds);
        $orderGoodsList = OrderServices::getInstance()->getOrderGoodsListByOrderIds($orderIds)->groupBy('order_id');

        $list = $orderList->map(function (Order $order) use ($orderGoodsList, $grouponOrderIds) {
            /** @var \Illuminate\Support\Collection $goodsList */
            $goodsList = $orderGoodsList->get($order->id);
            $goodsList = $goodsList->map(function (OrderGoods $orderGoods) {
                return OrderServices::getInstance()->coverOrderGoodsVo($orderGoods);
            });
            return OrderServices::getInstance()->coverOrderVo($order, $grouponOrderIds, $goodsList);
        });

        return $this->successPaginate($orderListWithPage, $list);
    }

    public function prepay(){
        $orderId=$this->verifyId('orderId');
        $order=OrderServices::getInstance()->getWxPayOrder($this->userId(),$orderId);
        Pay::wechat()->mp($order);
    }

    public function h5pay(){
        $orderId=$this->verifyId('orderId');
    }



}
