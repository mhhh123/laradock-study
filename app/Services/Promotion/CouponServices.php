<?php
namespace App\Services\Promotion;

use App\CodeResponse;
use App\Constant;
use App\Exceptions\BusinessException;
use App\Inputs\PageInput;
use App\Models\Order\Cart;
use App\Models\Promotion\Coupon;
use App\Models\Promotion\CouponUser;
use App\Models\Promotion\Groupon;
use App\Services\BaseServices;
use Carbon\Carbon;

class CouponServices extends BaseServices
{
    /**
     * @param int $userId
     * @return array
     * @throws BusinessException
     */
    public function queryByNew($userId = 0)
    {
        $input = PageInput::new();
        $input->limit = 3;
        if ($userId) {
            $pageData = $this->mylist($userId, 0, $input);
        } else {
            $pageData = $this->list($input);
        }

        $pageData = $pageData->toArray();
        return $pageData['data'] ?? [];

}

    public function getMeetPriceCouponAndSort($userId,$price){
        $couponUSers=CouponServices::getInstance()->getUsableCoupons($userId);
        $couponIds=$couponUSers->pluck('coupon_id')->toArray();
        $coupons=CouponServices::getInstance()->getCoupons($couponIds)->keyBy('id');
        return $couponUSers->filter(function (CouponUser $couponUser)use ($coupons, $price){
            $coupon=$coupons->get($couponUser->coupon_id);
            return $coupon->discount;
        });

    }

    public function getCouponUserByCouponId($userId,$couponId){
        return CouponUser::query()->where('user_id',$userId)->where('coupon_id',$couponId)
            ->orderBy('id')->first();
    }

    public function getMostMeetPriceCoupon($userId,$couponId,$price,&$availableCouponLenth)
    {

        if (is_null($couponId) || $couponId == -1) {
            return null;
        }

        $couponUsers = $this->getMeetPriceCouponAndSort($userId, $price);
        $availableCouponLenth=$couponUsers->count();
        if (!empty($couponId)) {
            $coupon = $this->getCoupon($couponId);
            $couponUser = $this->getCouponUserByCouponId($userId,$couponId);
            $is = $this->checkCouponAndPrice($coupon, $couponUser, $price);
            if ($is) {
                return $couponUser;
            }
        }
        return $couponUsers->first();
    }
    /**
     * @param $id
     * @param $columns
     * @return CouponUser|CouponUser[]|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function getCouponUser($id,$columns=['*']){
        return CouponUser::query()->find($id, $columns);
    }

    /**
     * @param Coupon $coupon
     * @param CouponUser $couponUser
     * @param double $price
     * @return false|void
     */
        public function checkCouponAndPrice($coupon,$couponUser,$price){
            if(empty($couponUser)){
                return false;
            }
            if(empty($coupon)){
                return false;
            }
            if($couponUser->coupon_id!=$coupon->id){
                return false;
            }
            if ($coupon->status!=Constant::STATUS_NORMAL){
                return false;
            }
            if ($coupon->goods_type!=Constant::GOODS_TYPE_ALL){
                return false;
            }
            if (bccomp($coupon->min,$price)==1){
                return false;
            }
            $now=now();
            switch ($coupon->time_type){
                case Constant::TIME_TYPE_TIME:
                    $start=Carbon::parse($coupon->start_time);
                    $end=Carbon::parse($coupon->end_time);
                    if ($now->isBefore($start)||$now->isAfter($end)){
                        return false;
                    }
                    break;
                case Constant::TIME_TYPE_DAYS:
                    $expired=Carbon::parse($couponUser->add_time)->addDays($coupon->days);
                    if ($now->isAfter($expired)){
                        return false;
                    }
                    break;
                default:
                    return false;
            }
            return true;

        }
        public function getUsableCoupons($userId){
            return CouponUser::query()->where('user_id',$userId)
                ->where('status',Constant::Coupon_STATUS_USABLE)
                ->get();
        }

        public function getCoupon($id,$columns=['*']){
            return Coupon::query()
            ->find($id,$columns);

    }

        public function getCoupons(array $ids,$columns=['*']){
                return Coupon::query()->whereIn('id',$ids)
                    ->get($columns);
}

        public function countCoupon($couponId){
                return CouponUser::query()->where('coupon_id',$couponId)
                    ->count('id');
            }

        public function countCouponByUserId($userId, $couponId){
            return CouponUser::query()->where('coupon_id',$couponId)
                ->where('user_id',$userId)
                ->count('id');
        }


        public function list(PageInput $page,$columns=['*']){
            return  Coupon::query()
                ->where('type',Constant::Type_COMMON)
                ->where('status',Constant::STATUS_NORMAL)
                ->orderBy($page->sort,$page->order)
                ->paginate($page->limit,$columns,'page',$page->page);
        }

        public function mylist($userId,$status,PageInput $page,$columns=['*']){
            return CouponUser::query()
                ->where('user_id',$userId)
                ->when(!is_null($status),function ($query)use ($status){
                    return $query->where('status',$status);})
                ->orderBy($page->sort,$page->order)
                ->paginate($page->limit,$columns,'page',$page->page);
        }

    /**
     * @param $userId
     * @param $couponId
     * @return bool|void
     * @throws BusinessException
     */
    public function receive($userId, $couponId)
    {
        $coupon = CouponServices::getInstance()->getCoupon($couponId);
        if (is_null($coupon)) {
            $this->throwBusinessException(CodeResponse::PARAM_ILLEGAL);
        }

        if ($coupon->total > 0) {
            $fetchedCount = CouponServices::getInstance()->countCoupon($couponId);
            if ($fetchedCount >= $coupon->total) {
                $this->throwBusinessException(CodeResponse::COUPON_EXCEED_LIMIT);
            }
        }

        if ($coupon->limit > 0) {
            $userFetchedCount = CouponServices::getInstance()->countCouponByUserId($userId, $couponId);
            if ($userFetchedCount >= $coupon->limit) {
                $this->throwBusinessException(CodeResponse::COUPON_EXCEED_LIMIT, '优惠券已经领取过');
            }
        }

        if ($coupon->type != Constant::Type_COMMON) {
            $this->throwBusinessException(CodeResponse::COUPON_RECEIVE_FAIL, '优惠券类型不支持');
        }

        if ($coupon->status == Constant::STATUS_OUT) {
            $this->throwBusinessException(CodeResponse::COUPON_EXCEED_LIMIT);
        }

        if ($coupon->status == Constant::STATUS_EXPIRED) {
            $this->throwBusinessException(CodeResponse::COUPON_RECEIVE_FAIL, '优惠券已经过期');
        }

        $couponUser = CouponUser::new();
        if ($coupon->time_type == Constant::TIME_TYPE_TIME) {
            $startTime = $coupon->start_time;
            $endTime = $coupon->end_time;
        } else {
            $startTime = Carbon::now();
            $endTime = $startTime->copy()->addDays($coupon->days);
        }

        $couponUser->fill([
            'coupon_id' => $couponId,
            'user_id' => $userId,
            'start_time' => $startTime,
            'end_time' => $endTime
        ]);

        return $couponUser->save();
    }


    }
