<?php
namespace App\Services\User;

use App\CodeResponse;
use App\Exceptions\BusinessException;
use App\Models\User;
use App\Notifications\VerificationCode;
use App\Services\BaseServices;
use Carbon\Carbon;
use Faker\Provider\PhoneNumber;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Notification;
use Leonis\Notifications\EasySms\Channels\EasySmsChannel;

class UserServices extends BaseServices
{

    public function getUserById($id){
        return User\User::query()->find($id);
    }

    public static function getInstance()
    {
        if (static::$instance instanceof static) {
            return static::$instance;
        }
        static::$instance = new static();
        return static::$instance;
    }

    /**
     * @param array $userIds
     * @return Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
     */
    public function getUsers(array $userIds){
        if(empty($userIds)){
            return collect([]);
        }
        return User\User::query()->whereIn('id', $userIds)->get();
    }
    /**
     *根据用户名获取用户
     * @param $username
     * @return User\User|null|Model
     */
    public function getByusername($username)
    {
        return User\User::query()->where('username', $username)
            ->where('deleted', 0)->first();
    }

    /**
     * @param $mobile
     * @return User\User|null|Model
     */
    public function getByMobile($mobile)
    {
        return User\User::query()->where('mobile',$mobile)->first();
    }
    /**
     *
     * @param string $mobile
     * @return bool
     */
    public function checkMobileSendCaptchaCount(string $mobile)
    {
        $countKey = 'register_captcha_count_' . $mobile;
        if (Cache::has($countKey)) {
            $count = Cache::increment('register_captcha_count_' . $mobile);
            if ($count > 10) {
                return false;
            }
        } else {
            Cache::put($countKey, 1, Carbon::tomorrow()->diffInSeconds(now()));

        }
        return true;
    }

    /**
     * @param string $mobile
     * @param string $code
     * @return array|int
     */
    public function sendCaptchaMsg(string $mobile, string $code)
    {
        if(app()->environment('testing')) {
            return 1;
        }
        Notification::route(
            EasySmsChannel::class,
            new PhoneNumber($mobile, 86)
        )->notify(new VerificationCode($code));
        return CodeResponse::SUCCESS;
    }

    /**
     * @param string $mobile
     * @param string $code
     * @return bool
     * @throws BusinessException
     */
    public function checkCaptcha(string $mobile,string $code){
        if (!app()->environment('production')){
            return  true;
        }
        $key='register_captcha_'.$mobile;
        $isPass=$code ===Cache::get($key);
        if ($isPass){
            Cache::forget($key);
            return true;
        }else{
            throw new BusinessException(CodeResponse::AUTH_CAPTCHA_UNMATCH);
        }
    }

    /**
     * @param string $mobile
     * @return int
     * @throws \Exception
     */
    public function setCaptcha(string $mobile){
        $code =random_int(100000, 999999);
        Cache::put('register_captcha_'.$mobile, $code, 600);
        return $code;
    }
}


