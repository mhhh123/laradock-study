<?php
namespace App\Services\Goods;
use App\Models\Goods\Brand;
use App\Services\BaseServices;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;



class BrandServices extends BaseServices
{

    public function getFront()
    {
        $result = $this->getBrandList(1, 4 , '', '');
        $result = $result->toArray();
        return $result['data'] ?? [];
    }

    /**
     * @param $id
     * @return Builder|Builder[]|Collection|Model|null
     */
    public function getBrand($id){
        return Brand::query()->find($id);
    }

    /**
     * @param int $page
     * @param int $limit
     * @param $sort
     * @param $order
     *@return LengthAwarePaginator
     */
    public function getBrandList(int $page, int $limit, $sort, $order){
        $query=Brand::query();
        if (!empty($sort)&&!empty($order)){
            $query->orderBy($sort, $order);
        }
        return $query->paginate($limit,['*'],'page',$page);

    }

}


