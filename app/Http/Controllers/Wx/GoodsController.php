<?php
namespace App\Http\Controllers\Wx;

use App\CodeResponse;
use App\Constant;
use App\Inputs\GoodsListInput;
use App\Services\CollectServices;
use App\Services\CommentServices;
use App\Services\Goods\BrandServices;
use App\Services\Goods\CatalogServices;
use App\Services\Goods\GoodsServices;
use App\Services\SearchHistoryServices;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GoodsController extends WxController{
    protected $only=[];

    /**
     * @return JsonResponse
     */
    public function count(){
        $count= GoodsServices::getInstance()->countonsale();
        return $this->success($count);
    }

    public function category(Request $request){
        $id= $request->input('id',0);

        if (empty($id)){
            return $this->fail(CodeResponse::PARAM_ILLEGAL);
        }
        $cur=CatalogServices::getInstance()->getCategoryById($id);
        if(empty($cur)){
            return $this->fail(CodeResponse::PARAM_ILLEGAL);
        }

        if($cur->pid==0){
            $parent=$cur;
            $children=CatalogServices::getInstance()->getL2listBypid($cur->pid);
            $cur=$children->first()??$cur;
        }else{
            $parent=CatalogServices::getInstance()->getL1ById($cur->pid);
            $children=CatalogServices::getInstance()->getL2listBypid($cur->pid);
        }
            return $this->success([
                'currentCategory'=>$cur,
                'parentCategory'=>$parent,
                'childrenCategory'=>$children
            ]);
        }

    /**
     * @return JsonResponse
     * @throws \App\Exceptions\BusinessException
     */
    public function list(){
        $input=GoodsListInput::new('add');
        if ($this->isLogin() && !empty($keyword)){
            SearchHistoryServices::getInstance()->save($this->userId(),$keyword,Constant::SEARCH_HISTORY_FROM_WX) ;
        }

        $columns=['id','name','brief','pic_url','is_new','is_hot','counter_price','retail_price'];
        $goodsList=GoodsServices::getInstance()->listGoods($input, $columns);
        $categoryList=GoodsServices::getInstance()->listL2Category($input);
        $goodsList=$this->paginate($goodsList);
        $goodsList['filterCategoryList']=$categoryList;
        return $this->success($goodsList);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
        public function detail(Request $request)
        {
            $id = $request->input('id');
            if (empty($id)) {
                return $this->fail(CodeResponse::PARAM_ILLEGAL);
            }
            $info = GoodsServices::getInstance()->getGoods($id);
            if (empty($info)) {
                return $this->fail(CodeResponse::PARAM_ILLEGAL);
            }
            $attr = GoodsServices::getInstance()->getGoodsAttribute($id);
            $spec = GoodsServices::getInstance()->getGoodsSpecification($id);

            $product = GoodsServices::getInstance()->getGoodsProduct($id);
            $issue = GoodsServices::getInstance()->getGoodsIssue($id);

            $brand = $info->brand_id ? BrandServices::getInstance()->getBrand($info->brand_id) : (object)[];
            $comment = CommentServices::getInstance()->getCommentWithUserInfo($id);

            $userHasCollect = 0;
            if ($this->isLogin()) {
                $userHasCollect = CollectServices::getInstance()->countByGoodsId($this->userId(), $id);
                GoodsServices::getInstance()->saveFootPrint($this->userId(), $id);
            }
            return $this->success([
                'info' => $info,
                'userHasCollect' => $userHasCollect,
                'issue' => $issue,
                'comment' => $comment,
                'specificationList' => $spec,
                'productList' => $product,
                'attribute' => $attr,
                'brand' => $brand,
                'groupon' => [],
                'share' => ''
            ]);
        }
}
