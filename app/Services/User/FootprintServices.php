<?php
namespace App\Services\User;

use App\Inputs\PageInput;
use App\Models\Goods\FootPrint;
use App\Models\Goods\Goods;
use App\Services\BaseServices;

class FootprintServices extends BaseServices
{
    public function getfootPrint($userId,PageInput $page,$columns=['*']){
        return FootPrint::query()
            ->where('user_id',$userId)
            ->where('deleted',0)
            ->orderBy($page->sort,$page->order)
            ->paginate($page->limit,$columns,'page',$page->page);
    }
    public function getGoods(array $ids, $columns=['*']){
        return Goods::query()
            ->whereIn('id',$ids)
            ->get($columns);
    }


}


