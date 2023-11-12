<?php
namespace App\Http\Controllers\Wx;

use App\CodeResponse;

use App\Exceptions\BusinessException;

use App\Inputs\PageInput;
use App\Models\Promotion\Coupon;
use App\Models\Promotion\CouponUser;
use App\Services\Goods\GoodsServices;
use App\Services\Order\CartServices;
use App\Services\Order\OrderServices;
use App\Services\Promotion\CouponServices;
use App\Services\Promotion\GrouponServices;
use App\Services\SystemServices;
use App\Services\User\AddressServices;
use App\Services\User\FootprintServices;
use App\Services\User\USerTestService;
use Illuminate\Http\JsonResponse;



class FootPrintController extends WxController
{
    /**
     * @return JsonResponse
     * @throws BusinessException
     */
    public function list(){
        {
            $page = PageInput::new();
            $list = FootprintServices::getInstance()->getfootPrint($this->userId(),$page);
            $footList = collect($list->items());
            $footIds = $footList->pluck('goods_id')->toArray();
            $goods = GoodsServices::getInstance()->getGoods($footIds)->keyBy('id');
            $result=[];
            foreach ($list as $item){
                $good=$goods->find($item->goods_id);
                $result[]=
                    [
                        'addTime'=>$item->add_time,
                        'brief'=>$good->brief,
                        'goodsId'=>$item->goods_id,
                        'id'=>$item->id,
                        'name'=>$good->name,
                        'picUrl'=>$good->pic_url,
                        'retailPrice'=>$good->retail_price
                    ];
            }
            return $this->successpaginate($result);
        }
        }
}
