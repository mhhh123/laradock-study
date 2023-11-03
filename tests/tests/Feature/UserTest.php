<?php

namespace Tests\Feature;

use App\Services\User\UserService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserTest extends TestCase
{
    // 为了防止脏数据,不会真的写入数据
    use DatabaseTransactions;

    public function testRegisterErrCode()
    {
        $response = $this->post('/wx/auth/register', [
            'username' => 'test03',
            'password' => '123456',
            'mobile' => '18811113333',
            'code' => '123'
        ]);
        $response->assertJson([
            'errno' => 703,
            'errmsg' => '验证码错误'
        ]);
    }

    public function testRegister()
    {
        //  设置验证码
        $code = UserService::getInstance()->setCaptcha('18811113333');

        $response = $this->post('/wx/auth/register', [
            'username' => 'test03',
            'password' => '123456',
            'mobile' => '18811113333',
            'code' => $code
        ]);

        // 断言
        $response->assertStatus(200);

        // 拿到原始值
        $ret = $response->getOriginalContent();

        // 断言对比
        $this->assertEquals(0, $ret['errno']);

        // 断言对比,不为空
        $this->assertNotEmpty($ret['data']);
    }

    /**
     * 异常的情况
     */
    public function testRegisterMobile()
    {
        $response = $this->post('/wx/auth/register', [
            'username' => 'test13',
            'password' => '123456',
            'mobile' => '153567887651',
            'code' => '123'
        ]);

        // 断言
        $response->assertStatus(200);

        // 拿到原始值
        $ret = $response->getOriginalContent();

        // 断言对比
        $this->assertEquals(707, $ret['errno']);
    }

    /**
     * 发送验证码
     */
    public function testRegCaptcha()
    {
        $response = $this->post('/wx/auth/regCaptcha', ['mobile' => '18811112222']);
        $response->assertJson(['errno' => 0, 'errmsg' => '成功']);
        $response = $this->post('/wx/auth/regCaptcha', ['mobile' => '18811112222']);
        $response->assertJson(['errno' => 702, 'errmsg' => '验证码未超时1分钟，不能发送']);
    }

    /**
     * 验证登录
     */
    public function testLogin()
    {
        $response = $this->post('/wx/auth/login', ['username' => 'zq2', 'password' => '123456']);
        // dd($response->getOriginalContent());
        $response->assertJson([
            "errno" => 0,
            "errmsg" => "成功",
            "data" => [
                "userInfo" => [
                    "nickName" => "zq2",
                    "avatarUrl" => "https://yanxuan.nosdn.127.net/80841d741d7fa3073e0ae27bf487339f.jpg?imageView&quality=90&thumbnail=64x64"
                ],
            ]
        ]);
        echo $response->getOriginalContent()['data']['token'] ?? '';
        // token是否为空
        $this->assertNotEmpty($response->getOriginalContent()['data']['token'] ?? '');
    }

    /**
     * 验证获取用户
     */
    public function testInfo()
    {
        $response = $this->post('/wx/auth/login', ['username' => 'zq2', 'password' => '123456']);
        $token = $response->getOriginalContent()['data']['token'] ?? '';
        $response2 = $this->get('wx/auth/info', ['Authorization' => 'Bearer {$token}']);
        $user = UserService::getInstance()->getByUsername('zq2');
        $response2->assertJson([
            'data' => [
                'nickName' => $user->nickname,
                'avatar' => $user->avatar,
                'gender' => $user->gender,
                'mobile' => $user->mobile
            ]
        ]);
    }

    // 测试退出系统
    public function testLogout()
    {
        $response = $this->post('/wx/auth/login', ['username' => 'zq2', 'password' => '123456']);
        $token = $response->getOriginalContent()['data']['token'] ?? '';
        $response2 = $this->get('wx/auth/info', ['Authorization' => 'Bearer {$token}']);
        $user = UserService::getInstance()->getByUsername('zq2');
        $response2->assertJson([
            'data' => [
                'nickName' => $user->nickname,
                'avatar' => $user->avatar,
                'gender' => $user->gender,
                'mobile' => $user->mobile
            ]
        ]);

        $response3 = $this->post('/wx/auth/logout', [], ['Authorization' => 'Bearer {$token}']);
        $response3->assertJson(['errno' => 0]);
        $response4 = $this->get('wx/auth/info', ['Authorization' => 'Bearer {$token}']);
        $response4->assertJson(['errno' => 501]);
    }

    // 测试 密码重置
    public function testReset()
    {
        $mobile = '15100000000';
        //  设置验证码
        $code = UserService::getInstance()->setCaptcha($mobile);

        $response = $this->post('/wx/auth/reset', [
            'mobile' => $mobile,
            'password' => 'user1234',
            'code' => $code
        ]);

        $response->assertJson(['errno' => 0]);
        $user = UserService::getInstance()->getByMobile($mobile);
        $isPass = Hash::check('user1234', $user->password);
        $this->assertTrue($isPass);
    }

    // 测试 账户信息修改
    public function testProfile()
    {
        $response = $this->post('/wx/auth/login', ['username' => 'zq2', 'password' => '123456']);
        $token = $response->getOriginalContent()['data']['token'] ?? '';
        $response = $this->post('/wx/auth/profile', [
            'avatar' => '',
            'gender' => 1,
            'nickname' => 'user1234'
        ], ['Authorization' => 'Bearer '.$token]);
        $response->assertJson(['errno' => 0]);
        $user = UserService::getInstance()->getByUsername('user123');
        $this->assertEquals('user1234', $user->nickname);
        $this->assertEquals(1, $user->gender);
    }
}