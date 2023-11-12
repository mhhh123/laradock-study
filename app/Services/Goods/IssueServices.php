<?php
namespace App\Services\Goods;
use App\Inputs\PageInput;
use App\Models\Goods\Brand;
use App\Models\Goods\issue;
use App\Services\BaseServices;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;



class IssueServices extends BaseServices
{
        public function getIssue(PageInput $page,$columns=['*']){
            return issue::query()->where('deleted',0)->orderBy($page->sort,$page->order)
                ->paginate($page->limit,$columns,'page',$page->page);
        }


}


