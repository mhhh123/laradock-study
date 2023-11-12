<?php
namespace App\Http\Controllers\Wx;

use App\Inputs\PageInput;
use App\Services\BaseServices;
use App\Services\Goods\IssueServices;

class IssueController extends WxController {
    public function list(){
        $page=PageInput::new();
        $IssueList=IssueServices::getInstance()->getIssue($page);
        $list=$IssueList->items();
        return $this->successpaginate($list);
        }
}
