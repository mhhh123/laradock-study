<?php

namespace Tests\Unit;

use App\CodeResponse;
use App\Exceptions\BusinessException;
use App\Services\User\UserService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class AuthTest extends TestCase
{
    // 为了防止脏数据,不会真的写入数据
    use DatabaseTransactions;

    // 防刷验证, 当天只能请求10次
    public function testCheckMobileSendCaptchaCount()
    {
        $mobile = '13212341991';
        foreach (range(0, 9) as $i) {
            $isPass = UserService::getInstance()->checkMobileSendCaptchaCount($mobile);
            $this->assertTrue($isPass);
        }
        $isPass = UserService::getInstance()->checkMobileSendCaptchaCount($mobile);
        $this->assertFalse($isPass);

        $countKey = 'register_captcha_count_'.$mobile;
        Cache::forget($countKey);
        $isPass = UserService::getInstance()->checkMobileSendCaptchaCount($mobile);
        $this->assertTrue($isPass);
    }

    // 验证短信验证码是否有效
    public function testCheckCaptcha()
    {
        $mobile = '13111111111';
        $code = UserService::getInstance()->setCaptcha($mobile);
        $isPass = UserService::getInstance()->checkCaptcha($mobile, $code);
        $this->assertTrue($isPass);

        $this->expectException(BusinessException::class);
        // $this->expectExceptionCode(703);
        $this->expectExceptionObject(new BusinessException(CodeResponse::AUTH_CAPTCHA_UNMATCH));
        UserService::getInstance()->checkCaptcha($mobile, $code);
    }

}