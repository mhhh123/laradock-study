<?php
namespace App\Http\Controllers\Wx;

use App\Exceptions\BusinessException;
use App\Inputs\PageInput;

use App\Models\Promotion\CouponUser;
use App\Services\Promotion\CouponServices;
use Illuminate\Http\JsonResponse;

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
    public function mylist()
    {
        $status = $this->verifyInteger('status', 0);
        $page = PageInput::new();
        $list = CouponServices::getInstance()->mylist($this->userId(), $status, $page);

        $couponUserList = collect($list->items());
        $couponIds = $couponUserList->pluck('coupon_id')->toArray();
        $coupons = CouponServices::getInstance()->getCoupons($couponIds);
        $mylist = $couponUserList->map(function (CouponUser $items) use ($coupons) {
            $coupon = $coupons->get($items->coupon_id);
            return [
                'id' => $items->id,
                'cid' => $coupon->id,
                'name' => $coupon->name,
                'desc' => $coupon->items,
                'tag' => $coupon->tag,
                'min' => $coupon->min,
                'discount' => $coupon->dicount,
                'startTime' => $items->start_tiem,
                'endTime' => $items->end_tiem,
                'available' => false
            ];
        });

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
        CouponServices::getInstance()->rececive($this->userId(),$couponId);
        return $this->success();


    }

}
