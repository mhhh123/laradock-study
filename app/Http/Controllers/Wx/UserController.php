<?php
namespace App\Http\Controllers\Wx;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;


class UserController extends WxController
{
    protected $only = ['info', 'profile'];
    /**
     * 获取用户信息
     * @return JsonResponse
     */
    public function index()
    {
        $user =Auth::user();
        dd($user);

        return $this->success([
            'nickName' => $user->nickname,
            'avatar' => $user->avatar,
            'gender' => $user->gender,
            'mobile' => $user->mobile,
        ]);


}}


