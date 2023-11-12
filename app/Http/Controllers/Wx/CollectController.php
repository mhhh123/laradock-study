<?php
namespace App\Http\Controllers\Wx;

use App\CodeResponse;
use App\Inputs\PageInput;
use App\Models\Collect;
use App\Services\CollectServices;
use App\Services\User\USerTestService;
use Illuminate\Http\Request;

class CollectController extends  WxController
{
    public function addOrDeleted(Request $request)
    {
        $type = $request->input('type');
        $valueId = $request->input('valueId');
        if (is_null($type)) {
            return $this->fail(CodeResponse::PARAM_ILLEGAL);
        }
        $collect = CollectServices::getInstance()->getCollectByTypeandValue($this->userId(), $type, $valueId) ?? '';

        if (isset($collect->id)) {
            $id = $collect->id;
            return CollectServices::getInstance()->deletedByid($id, $this->userId());
        } else {
            $collect = new Collect();
            $collect->user_id = $this->userId();
            $collect->value_id = $valueId;
            $collect->type = $type;
            $collect->save();
        }

        return $this->success();
    }


    public function list(Request $request)
    {
        {
            $type=$request->input('type');
            $page = PageInput::new();
            $list = USerTestService::getInstance()->collect($this->userId(), $type, $page);
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
    }
}
