<?php
namespace App\Http\Controllers\Wx;

use App\Inputs\PageInput;
use App\Models\Collect;
use App\Models\Goods\Footprint;
use App\Models\Goods\Goods;
use App\Models\Promotion\Coupon;
use App\Models\Promotion\CouponUser;
use App\Services\Goods\GoodsServices;
use App\Services\Promotion\CouponServices;
use App\Services\User\FootprintServices;
use App\Services\User\USerTestService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;



class UserController extends WxController
{
    protected $only = ['info', 'profile'];

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $user = auth('wx')->user();;
        return $this->success([
            'nickName' => $user->nickname,
            'avatar' => $user->avatar,
            'gender' => $user->gender,
            'mobile' => $user->mobile
        ]);
    }

    public function test()
    {
        $type = 0;
        $userId = 1;
        $page = PageInput::new();
        $list = USerTestService::getInstance()->collect($userId, $type, $page);
        $collectUserList = collect($list->items());
        $collectIds = $collectUserList->pluck('value_id')->toArray();
        $goods = USerTestService::getInstance()->getGoods($collectIds);

        $mylist = $collectUserList->map(function (Collect $item) use ($goods) {
            $good = $goods->get($item->Id);

            return [
                'brief' => $good->brief,
                'id' => $item->id,
                'name' => $good->name,
                'picUrl' => $good->pic_url,
                'retailPrice' => $good->retail_price,
                'type' => $item->type,
                'valueId' => $item->value_id
            ];
        });
        $list = $this->paginate($list);
        $list['list'] = $mylist;
        return $this->success($list);
    }

    //$item为空
    public function test2()
    {
        $userId = 1;
        $status = 2;
        $page = PageInput::new();
        $list = CouponServices::getInstance()->mylist($userId, $status, $page);
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


}
















