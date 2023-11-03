<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    public function getAuthHeader()
    {
        $response=$this->post('wx/auth/login',['username'=>'user1', 'password'=>'123456', 'mobile'=>'18837613237']);
        $token=$response->getOriginalContent()['data']['token'] ?? '';
        return ['Authorization'=>"Bearer {$token}"];
    }



}
