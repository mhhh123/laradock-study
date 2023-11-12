<?php
namespace App\Services\User;


use App\Inputs\PageInput;
use App\Models\Collect;
use App\Models\Goods\Goods;
use App\Services\BaseServices;

class USerTestService extends BaseServices{
    public function needCollect(Collect $collect){
            return [

                'id'=>$collect->id,
                'type'=>$collect->type,
                'value_id'=>$collect->value_id
            ];
    }

    public function collect($userId,$type,PageInput $page,$columns=['*']){
        return Collect::query()
            ->where('user_id',$userId)
            ->where('type',$type)
            ->where('deleted',0)
            ->orderBy($page->sort,$page->order)
            ->paginate($page->limit,$columns,'page',$page->page);
    }

    public function getGoods(array $ids, $columns=['*']){
        return Goods::query()->whereIn('id',$ids)->get($columns)->keyBy('value_id');
    }

    public function getTopic(array $ids, $columns=['*']){
        return Goods::query()->whereIn('id',$ids)->get($columns)->keyBy('value_id');
    }
}
