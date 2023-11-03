<?php
namespace App\Http\Controllers\Wx;

use App\CodeResponse;
use App\Models\User;
use App\Notifications\VerificationCode;
use App\Services\User\UserServices;
use Carbon\Carbon;
use Faker\Provider\PhoneNumber;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Leonis\Notifications\EasySms\Channels\EasySmsChannel;



class AuthController extends WxController
{
    protected $only=['info','profile'];

    /**
     *
     *
     */
    public function __construct()
    {
        $this->middleware('auth:wx');
    }

    public function user()
    {
       $user=Auth::guard('wx')->user();
       return $this->success($user);
    }

    /**
     * 获取用户信息
     * @return JsonResponse
     */
    public function info()
    {
        $user = $this->user();
        return $this->success([
            'nickName' => $user->nickname,
            'avatar' => $user->avatar,
            'gender' => $user->gender,
            'mobile' => $user->mobile,
        ]);
    }

    /**密码重置
     * @param Request $request
     * @return JsonResponse
     */
    public function profile(Request $request)
    {
        $user=$this->user();
        $avatar=$request->input('avatar');
        $gender=$request->input('gender');
        $nickname=$request->input('nickename');

        if(!empty($avatar)){
            $user->avatar=$avatar;
        }
        if(!empty($gender)){
            $user->gender=$gender;
        }
        if(!empty($nickname)){
            $user->nickname=$nickname;
        }
        $ret=$user->save();
        return $this->failorSuccess($ret, CodeResponse::UPDATED_FAIL);
    }


    /**登出
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request){

        $username=$request->input('username');
        $password=$request->input('password');

        //数据验证
        if(empty($username) || empty($password)){
            return $this->fail(CodeResponse::PARAM_ILLEGAL);
        }

        //验证账号是否存在
       $user = UserServices::getInstance()->getByUsername($username);
        if (is_null($user)){
            return $this-> fail( CodeResponse::AUTH_INVALID_ACCOUNT);
        }

        //对密码进行验证
        $isPass= Hash::check($password, $user->getAuthPassword());
        if (!$isPass){
            return $this->fail(CodeResponse::AUTH_INVALID_ACCOUNT);
        }

        //更新登录信息
        $user->last_login_time=now()->toDateTimeString();
        $user->last_login_ip=$request->getClientIP() ;
        if(!$user->save()) {
            return $this->fail(CodeResponse::UPDATED_FAIL);
        }
        //获取token
        $token=Auth::guard('wx')->login($user);
        //组装数据并返回
        return $this->success([
            'token'=>$token ,
            'userInfo'=>[
                'nickName'=>$username,
                'avatarUrl'=>$user->avatar
            ]
        ]);
    }

    /**密码重置
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function reset(Request $request)
    {
        $password=$request->input('password');
        $mobile=$request->input('mobile');
        $code=$request->input('code');

        if (empty($password)| empty($mobile)| empty($code)){
            return $this->fail(CodeResponse::PARAM_ILLEGAL);
        }

        $isPass=UserServices::getInstance()->checkCaptcha($mobile, $code);
        if(!$isPass){
            return $this->fail(CodeResponse::AUTH_CAPTCHA_UNMATCH);
        }
        $user=UserServices::getInstance()->getByMobile($mobile);

        if (is_null($user)){
            return $this->fail(CodeResponse::AUTH_MOBILE_UNREGISTERED);
        }
        $user->password = Hash::make($password);
        $ret=$user->save();
        return $this->failorSuccess($ret,CodeResponse::UPDATED_FAIL);

        //return $ret ?$this->success() :$this->fail(CodeResponse::UPDATED_FAIL);



    }

    /**发送短信验证码
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function regCaptcha(Request $request)
    {
        //获取手机号
        $mobile = $request->input('mobile');
        //验证手机号是否合法
        if (empty($username) | empty($password) | empty($mobile) | empty('$code')) {
            return $this->fail(CodeResponse::PARAM_ILLEGAL);
        }
        $validator = Validator::make(['mobile' => $mobile], ['mobile' => 'regex:/^1[0-9]{10}$']);
        if ($validator->fails()) {
            return $this->fail(CodeResponse::AUTH_INVALID_MOBILE);
        }
        $user=UserServices::getInstance()->getBymobile($mobile);
        if (!is_null($user)) {
            return $this->fail(CodeResponse::AUTH_MOBILE_REGISTERED);
        }
        $code = random_int(10000, 999999);
        //防刷验证，一分钟只能请求一次 ,一天只能请求10次
        $lock = Cache::add('register_capthca_lock' . $mobile, 1, 60);
        if (!$lock) {
            return $this->fail(CodeResponse::AUTH_CAPTCHA_FREQUENCY);
        }
        $isPass = UserServices::getInstance()->checkMobileSendCaptchaCount($mobile);
        if (!$isPass) {
            return $this->fail(CodeResponse::AUTH_CAPTCHA_FREQUENCY);
        }
        //保存手机号和验证码的关系
        Cache::put('register_captcha_' . $mobile, $code, 600);
        UserServices::getInstance()->sendCaptchaMsg($mobile, $code);
        return $this->fail(CodeResponse::SUCCESS);
    }

    /**注册
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function register(Request $request)
    {   //获取参数
        $username=$request->input('$username');
        $password=$request->input('$password');
        $mobile=$request->input('mobile');
        $code=$request->input('code');
        //验证参数是否为空
        if (empty($username)|empty($password)|empty($mobile)|empty('$code')){
            return  $this->fail(CodeResponse::PARAM_ILLEGAL);
        }
        //验证用户已存在
        $username=UserServices::getInstance()->getByusername($username);
        if (!is_null($username)){
            return $this->fail(CodeResponse::AUTH_NAME_REGISTERED);
        }
        $validator=Validator::make(['mobile'=>$mobile],['mobile'=>'regex:/^1[0-9]{10}$']);
        if ($validator->fails()){
            return $this->fail(CodeResponse::AUTH_INVALID_MOBILE);
        }
        $user= UserServices::getInstance()->getBymobile($mobile);
        if (!is_null($user)){
            return $this->fail(CodeResponse::AUTH_MOBILE_REGISTERED);
        }
        //随机生成6位验证码
        $code=random_int(10000,999999);
        //防刷验证，一分钟只能请求一次 ,一天只能请求10次
        $lock=Cache::put('register_capthca_'.$mobile, $code,'600');
        if(!$lock){
            return $this->fail(CodeResponse::AUTH_CAPTCHA_FREQUENCY);
        }
        $countKey= Cache::increment('register_captcha_count_'.$mobile);
        if(Cache::has($countKey)) {
            $count=Cache::increment('register_captcha_count_'.$mobile);
            if ($count > 10) {
                return $this->fail(CodeResponse::AUTH_CAPTCHA_FREQUENCY);

            } else {
                Cache::put($countKey,1, Carbon::tomorrow()->diffInSeconds(now()));
        }
            //保存手机号和验证码的关系
            Cache::put('register_captcha_'.$mobile, $code,600);
            //发送短信
            Notification::route(
                EasySmsChannel::class,
                new PhoneNumber($mobile, 86)
            )->notify(new VerificationCode($code));
            return $this->fail(CodeResponse::SUCCESS);
        }


    //写入用户表
        $user=new User\User();
        $user->username=$username;
        $user->password=Hash::make($password);
        $user->mobile=$mobile;
        $user->avatar='';
        $user->nickname=$username;
        $user->last_login_time=Carbon::now()->toDateTimeString();
        $user->last_login_ip=$request->getClientIp();
        $user->save();
    //返回用户信息和token
        return $this->success([
            'token'=>'',
            'userInfo'=>[
                'nickname'=>$username,
                'avatarUrl'=>$user->avatar
            ]
        ]);

    }
}
