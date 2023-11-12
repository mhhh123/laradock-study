<?php
namespace App\Http\Controllers\Wx;

use App\Exceptions\BusinessException;
use App\Inputs\PageInput;

use App\Models\Promotion\CouponUser;
use App\Services\Promotion\CouponServices;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CouponController extends WxController
{
    protected $except = ['list'];

    /**
     * @优惠劵列表
     * @return JsonResponse
     * @throws BusinessException
     */
    public function list()
    {
        $page = PageInput::new();
        $columns = ['id', 'name', 'desc', 'tag', 'discount', 'min', 'days', 'start_time', 'end_time'];
        $list = CouponServices::getInstance()->list($page, $columns);
        return $this->successpaginate($list);

    }

    /**
     * @return JsonResponse
     * @throws BusinessException
     */
    public function mylist(Request $request){
        $status=$request->input('status');
        $page = PageInput::new();
        $list = CouponServices::getInstance()->mylist($this->userId(), $status, $page);
        $couponUserList = collect($list->items());
        $couponIds = $couponUserList->pluck('coupon_id')->toArray();

        $coupons = CouponServices::getInstance()->getCoupons($couponIds)->keyBy('value_id');
        $mylist = [];
        foreach ($list as $items) {
            $coupon = $coupons->find($items->coupon_id);
            $mylist[] = [
                'id' => $items->id,
                'cid' => $coupon->id,
                'name' => $coupon->name,
                'desc' => $coupon->desc,
                'tag' => $coupon->tag,
                'min' => $coupon->min,
                'discount' => $coupon->discount,
                'startTime' => $items->start_time,
                'endTime' => $items->end_time,
                'available' => false
            ];
        }

        $list = $this->paginate($list, $mylist);
        return $this->success($list);
    }


    /**领取优惠劵
     * @return JsonResponse
     * @throws BusinessException
     */
    public function receive()
    {   //获取优惠卷id 获取失败返回失败
        $couponId = $this->verifyId('couponId', 0);
        CouponServices::getInstance()->receive($this->userId(),$couponId);
        return $this->success();


    }

}

