<?php


namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\User\UserServices;
use Tests\TestCase;

class AuthTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function testRegister(){
        $response = $this->post('wx/auth/register', [
            'username'=>'mh',
            'password'=>'123456',
            'moblie'=>'18837613237',
            'code'=>'1234'
        ]);
        $response->assertStatus(200);
        $ret= $response->getOriginalContent();
        $this->assertEquals(0,$ret['errno']);
        $this->assertEmpty($ret['data']);
    }
    public function testRegCaptcha(){
        $response = $this->post('wx/auth/register', [
            'moblie'=>'18837613237',
        ]);
        $response->assertJson(['errno'=>0, 'errmsg'=>'成功','data'=>Null]);
        $response->assertStatus(200);
        $ret= $response->getOriginalContent();
        $this->assertEquals(707,$ret['errno']);
    }
    public function testretset()
    {
        $code=UserServices::getInstance()->setCaptcha('18837613237');
        $response=$this->post('wx/auth/retset',['mobile'=>'18837613237','username'=>'user1','password'=>'123456','code'=>$code]);
        $response->assertJson(['errno'=>0]);
        $user=UserServices::getInstance()->getByMobile($mobile);

    }
}

