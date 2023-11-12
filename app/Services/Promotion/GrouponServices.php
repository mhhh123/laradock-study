<?php
namespace App\Services\Promotion;
use App\CodeResponse;
use App\Constant;
use App\Inputs\PageInput;
use App\Models\Promotion\Groupon;
use App\Models\Promotion\GrouponRules;
use App\Services\BaseServices;
use Carbon\Carbon;
use PhpParser\Builder;


class GrouponServices extends BaseServices
{

    public function getGrouponRules(PageInput $page,$columns=['*']){
       return GrouponRules::query()
           ->whereStatus(Constant::GROUP_RULE_STATUS_ON)
            ->orderBy($page->sort,$page->order)
            ->paginate($page->limit,$columns,'page',$page->page);
    }

    public function getGrouponRulesByIds($ids,$columns=['*'])
    {
        return GrouponRules::query()->find($ids,$columns);

    }

    public function countGrouponjoin($openGrouponId){
        return Groupon::whereGrouponId($openGrouponId)->where('status','!=1',Constant::GROUP_STATUS_NONE)
            ->count(['id']);
    }

    /**用户是否参与或开启某个团购
     * @param $userId
     * @param $grouponId
     * @return bool
     */
    public function isOpenOrjoin($userId,$grouponId){
        return Groupon::query()->whereUserId($userId)
            ->where(function (Builder $builder)use ($grouponId){
                return $builder->where('groupon_id',$grouponId)
                    ->orWhere('id',$grouponId);
            })->where('status','!=',Constant::GROUP_STATUS_NONE)->exists();
    }

    /**校验用户是否可以开启或参与某个团购活动
     * @param $userId
     * @param $ruleId
     * @param $linkId
     * @return mixed|null
     * @throws \App\Exceptions\BusinessException
     */
    public function checkGrouponVaild($userId,$ruleId,$linkId=null){
        if ($ruleId==null||$ruleId<=0){
            return null;
        }
        $rule=$this->getGrouponRulesByIds($ruleId);
        if (is_null($rule)){
            return $this->throwBusinessException(CodeResponse::PARAM_ILLEGAL);
        }
        if ($rule->status==Constant::GROUP_RULE_STATUS_DOWN_EXPIRE){
            $this->throwBusinessException(CodeResponse::GROUPON_EXPIRED);
        }
        if ($rule->status==Constant::GROUP_RULE_STATUS_DOWN_ADMIN){
            $this->throwBusinessException(CodeResponse::GROUPON_OFFLINE);
        }
        if ($this->countGrouponjoin($linkId)>=($rule->discount_member-1)){
            $this->throwBusinessException(CodeResponse::GROUPON_FULL);
        }
        if ($this->isOpenOrjoin($userId,$linkId)){
            $this->throwBusinessException(CodeResponse::GROUPON_JOIN);
        }
        return null;
    }
    public function getGroupon($id,$columns=['*']){
        return Groupon::query()->find($id,$columns);
    }
    public function openOrjoinGroupon($userId,$orderId,$ruleId,$linkId=null){
        if($ruleId==null||$ruleId<=0){
            return $linkId;
        }
        $groupon=Groupon::new();
        $groupon->order_id=$orderId;
        $groupon->user_id=$userId;
        $groupon->status=Constant::GROUP_STATUS_NONE;
        $groupon->rules_id=$ruleId;
        if($linkId==null||$linkId<=0){
            $groupon->creator_user_id=$userId;
            $groupon->creator_user_time=Carbon::now()->toDateTimeString();
            $groupon->groupon_id=0;
            $groupon->save();
            return $groupon->id;
        }
        $openGroupon=$this->getGroupon($linkId);
        $groupon->creator_user_id=$openGroupon->creator_user_id;
        $groupon->groupon_id=$linkId;
        $groupon->share_url=$openGroupon->share_url;
        $groupon->save();
        return  $linkId;
    }
    public function getGrouponOrderId($orderId){
        return Groupon::whereOrderId($orderId)->first();
    }

    /**支付成功 更新团购状态
     * @param $orderId
     * @return void|null
     * @throws \App\Exceptions\BusinessException
     */
    public function payGrouponOrder($orderId){
        $groupon=$this->getGrouponOrderId($orderId);
        if(is_null($groupon)){
            return null;
        }
        $rule=$this->getGrouponRulesByIds($groupon->rules_id);
        if ($groupon->groupon_id==0){
            $groupon->share_url=$this->createGrouponShareImage();
        }
        $groupon->status=Constant::GROUP_STATUS_ON;
        $isSuccess=$groupon->save();
        if (!$isSuccess){
            $this->throwBusinessException(CodeResponse::UPDATED_FAIL);
        }

        if ($groupon->groupon_id==0){
            return null;
        }

        $joinCount=$this->countGrouponjoin($groupon
        ->groupon_id);

        if($joinCount<$rule->discount_member-1){
            return ;
        }

        $row=Groupon::query()->where(function (Builder $builder)use ($groupon){
            return $builder->where('groupon_id',$groupon->groupon_id)
                ->orWhere('id',$groupon->groupon_id);})
            ->update(['status'=>Constant::GROUP_STATUS_SUCCEED]);

        if ($row==0){
            $this->throwBusinessException(CodeResponse::UPDATED_FAIL);
        }
        return ;
    }

    public function createGrouponShareImage(){
        return '';
    }

    public function getGrouponOrderInOrderIds($orderIds)
    {
        return Groupon::query()->whereIn('order_id', $orderIds)->pluck('order_id')->toArray();
    }
    }
