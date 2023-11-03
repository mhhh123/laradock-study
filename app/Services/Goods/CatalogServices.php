<?php
namespace App\Services\Goods;
use App\Models\Goods\Category;
use App\Services\BaseServices;
use Illuminate\Database\Eloquent\Builder;
use Ramsey\Collection\Collection;

class CatalogServices extends BaseServices
{
    /**获取一级类目列表
     * @return Category[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getL1List()
    {
        return Category::query()
            ->where('level','L1')
            ->get();
    }

    /**
     * @param int $pid
     * @return Category[]|Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getL2ListBypid(int $pid)
    {
        return Category::query()
            ->where('level','L2')
            ->where('pid',$pid)
            ->get();
    }

    /**根据id获取一级类目
     * @param int $id
     * @return Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function getL1ById(int $id)
    {
        return Category::query()->where('level','L1')
            ->where('id',$id)
            ->first();
    }
    public function getCategoryById(int $id){
        return Category::query()
            ->find($id);
    }

    public function getL2ById(array $ids){
        if (empty($ids)){
            return collect([]);
        }
        return Category::query()->whereIn('id',$ids)->get();
    }

    public function getCategory(int $id){
        return Category::query()->find($id);
    }
}

