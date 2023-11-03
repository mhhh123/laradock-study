<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AuthTest extends TestCase
{
    // 为了防止脏数据,不会真的写入数据
    use DatabaseTransactions;

    public function testLogin()
    {
        $response = $this->post('wx/auth/login', [
            'username' => 'user123',
            'password' => 'user1234'
        ]);
        $response->assertJson([
            "errno" => 0,
            "errmsg" => "成功",
            "data" => [
                "userInfo" => [
                    "nickName" => "user123",
                    "avatarUrl" => ""
                ]
            ]
        ]);
    }

    public function testInfo()
    {
        $response = $this->post('wx/auth/login', [
            'username' => 'user123',
            'password' => 'user1234'
        ]);
        $token = $response->getOriginalContent()['data']['token'] ?? '';
        $response2 = $this->get('wx/auth/info', ['Authorization' => "Bearer {$token}"]);
        $response2->assertJson(['data' => ['nickName' => 'user1234']]);
    }
}
