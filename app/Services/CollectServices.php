<?php
namespace App\Services;

use App\Constant;
use App\Inputs\PageInput;
use App\Models\Collect;

class CollectServices extends BaseServices
{
    public function countByGoodsId($userId, $goodsId){
        return Collect::query()->where('user_id',$userId)
            ->where('value_id',$goodsId)
            ->where('type',Constant::Collect_TYPE_GOODS)
            ->count('id');
    }

    public function count($id, $type, $userId)
    {
        return Collect::query()->where('user_id', $userId)
            ->where('id', $id)
            ->where('type', $type)
            ->count('id');
    }

    public function getCollectByTypeandValue($userId,$type,$valueId){
        return Collect::query()
            ->where('user_id',$userId)
            ->where('type',$type)
            ->where('value_id',$valueId)
            ->where('deleted',0)
            ->first();
    }

    public function deletedByid($id,$userId){
        return Collect::query()->where('user_id',$userId)
            ->where('id',$id)
            ->update(['deleted' => 1]);

    }


    /**
     * @param $userId
     * @param PageInput $page
     * @param $type
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getCollectListByType($userId,PageInput $page,$type,$column=['*']){
        return Collect::query()
            ->where('user_id',$userId)
            ->where('type',$type)
            ->orderBy($page->sort,$page->order)
            ->paginate($page->limit, $column, 'page', $page->page);


    }

    public function getCollectIdsBytype($userId,$type,$Id){
            return Collect::query()
                ->where('user_id',$userId)
                ->where('type',$type)
                ->find($Id);
    }


}


