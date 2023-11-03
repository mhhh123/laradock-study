<?php

namespace Tests\Unit;



use App\Services\User\UserServices;
use Tests\TestCase;

class AuthTest extends TestCase
{
    public function testCheckMobileSendCapthchaCount(){
        $mobile='18837613237';
        foreach (range(0,9)as $i){
            $isPass=(new UserServices())->checkMobileSendCaptchaCount($mobile);
            $this->assertTrue($isPass);
        }
        $isPass=(new UserServices())->checkMobileSendCaptchaCount($mobile);
        $this->assertFalse($isPass);
    }
}
