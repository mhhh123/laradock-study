<?php
namespace App\Services;

use App\Constant;
use App\Models\Comment;
use App\Services\User\UserServices;
use Illuminate\Support\Arr;

class CommentServices extends BaseServices
{
    public function getCommentBygoodsId($goodsId, $limit=2, $page=1,$sort='add_time',$order='desc'){
        return Comment::query()->where('value_id',$goodsId)
            ->where('type',Constant::COMMENT_TYPE_GOODS)

            ->orderBy($sort,$order)
            ->paginate($limit,['*'],'page',$page);
}
    public function getCommentWithUserInfo($goodsId, $page=1, $limit=2){
        $comments=$this->getCommentBygoodsId($goodsId, $page, $limit);
        $userIds=Arr::pluck($comments->items(), 'user_id');
        $userIds=array_unique($userIds);
        $users=UserServices::getInstance()->getUsers($userIds)->keyBy('id');
        $data=collect(($comments->items()))->map(function (Comment $comment)use ($users){
            $user=$users->get($comment->user_id);
            $comment=$comment->toArray();
            $comment=Arr::only($comment,['id','addTime','content', 'adminContent','picList']);
            $comment['nickname']=$user->nickname??'';
            $comment['avatar']=$user->avatar??'';
            return $comment;
        });
        return ['conut'=>$comments->total(),'data'=>$data];
    }
}


