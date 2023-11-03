<?php
namespace App\Services\Goods;

use App\Inputs\GoodsListInput;
use App\Models\Goods\FootPrint;
use App\Models\Goods\GoodsAttribute;
use App\Models\Goods\Goods;
use App\Models\Goods\GoodsProduct;
use App\Models\Goods\GoodsSpecification;
use App\Models\Goods\issue;
use App\Services\BaseServices;
use App\Services\SystemServices;
use Illuminate\Database\Eloquent\Builder;



class GoodsServices extends BaseServices
{

    /**
     * @throws \App\Exceptions\BusinessException
     */
    public function queryByNew()
    {
        $columns = ['id', 'name', 'brief', 'pic_url', 'is_new', 'is_hot', 'counter_price', 'retail_price'];
        $input = GoodsListInput::new('add');
        $input->isNew = true;
        $input->limit = SystemServices::getInstance()->getNewLimit();
        $goodsList = $this->listGoods($input, $columns);
        $goodsList = $goodsList->toArray();
        return $goodsList['data'] ?? [];
    }

    public function queryByHot()
    {
        $columns = ['id', 'name', 'brief', 'pic_url', 'is_new', 'is_hot', 'counter_price', 'retail_price'];
        $input = GoodsListInput::new('add');
        $input->isHot = true;
        $input->limit = SystemServices::getInstance()->getHotLimit();
        $goodsList = $this->listGoods($input, $columns);
        $goodsList = $goodsList->toArray();
        return $goodsList['data'] ?? [];
    }

    public function getGoodslistByIds(array $ids){
        if(empty($ids)){
            return collect();
        }
        return Goods::query()->whereIn('id',$ids)->get();
    }

    public function getGoods($id){
        return Goods::query()->find($id);
    }

    public function getGoodsAttribute(int $goodId){
        return GoodsAttribute::query()->where('goods_id',$goodId)
        ->get();
    }

    public function getGoodsSpecification(int $gooodsId){
        $spec= GoodsSpecification::query()->where('goods_id',$gooodsId)
        ->get()->groupBy('specification');
        return $spec->map(function ($v,$k){
            return ['name'=>$k, 'valueList'=>$v];
        })->values();
    }

    public function getGoodsProduct(int $goodsId)
    {
        return GoodsProduct::query()
            ->where('goods_id',$goodsId)
            ->get();
    }

    public function getGoodsProductByid(int $id){
        return GoodsProduct::query()->find($id);
    }

    public function getGoodsProductsByids(array $ids){
        if(empty($ids)){
            return collect();
        }
        return GoodsProduct::query()->whereIn('id',$ids)->get();
    }

    public function getGoodsIssue(int $goodsId, int $page=1,$limit=4){
        return issue::query()->forPage($page, $limit)->get();
}

    public function saveFootPrint($userId, $goodsId)
    {
        $footPrint = new FootPrint();
        $footPrint->fill(['user_id' => $userId, 'goods_id' => $goodsId]);
        return $footPrint->save();
    }

    /**
     * @return int
     */
    public function countonsale(){
        return  Goods::query()->where('is_on_sale',1)->count('id');
    }

    /**
     * @param GoodsListInput $input
     * @param $columns
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function listGoods(GoodsListInput $input, $columns)
       {
        $query=$this->getQueryByGoodsFilter($input);
        if (!empty($input->categoryId)){
            $query =$query->where('category_id',$input->categoryId);
        }
        return $query->orderBy($input->sort,$input->order)->paginate($input->limit,['*'],'page',$input->page);
    }

    /**
     * @param GoodsListInput $input
     * @return \App\Models\Goods\Category[]|Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function listL2Category(GoodsListInput $input)
    {
        $query = $this->getQueryByGoodsFilter($input);
        $categoryIds = $query->select(['category_id'])->pluck('category_id')->unique()->toArray();

        return CatalogServices::getInstance()->getL2ListBypid((int)$categoryIds);
    }

    private function getQueryByGoodsFilter(GoodsListInput $input){
        $query=Goods::query()->where('is_on_sale',1)
            ;
        if(!empty($input->brandId)){
            $query=$query->where('brand_id',$input->brandId);
        }
        if(!empty($input->isNew)){
            $query=$query->where('is_new',$input->isNew);
        }
        if(!empty($input->isHot)){
            $query=$query->where('is_hot',$input->isHot);
        }
        if(!empty($input->keyword)){
            $query=$query->where(function (Builder $query) use ($input){
                $query->where('keywords','like',"%$input->keyword%")->
                orWhere('name','like',"%$input->keyword%");
    });
    }
        return $query;
    }

    public function reduceStock($productId,$num){
        return GoodsProduct::query()->where('id',$productId)->where('number','>',$num)
            ->decrement('number',$num);
    }

    public function addStock($productId, $num)
    {
        $product = $this->getGoodsProductById($productId);
        $product->number = $product->number + $num;
        return $product->cas();
    }


}


