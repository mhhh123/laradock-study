<?php
// +----------------------------------------------------------------------
// | User: zq
// +----------------------------------------------------------------------
// | Time: 2021/12/6 17:19
// +----------------------------------------------------------------------

namespace Tests\Feature;

use App\Models\User\Address;
use GuzzleHttp\Client;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AddressTest extends TestCase
{
    // 为了防止脏数据,不会真的写入数据
    use DatabaseTransactions;

    // 收货地址列表
    public function testList()
    {
        $this->assertLitemallApiGet('wx/address/list');

        // $response = $this->get('wx/address/list', $this->getAuthHeader());
        // $client = new Client();
        // $response2 = $client->get('http://localhost:8080/wx/address/list',
        //     ['headers' => ['X-Litemall-Token' => $this->token]]);
        // $list = json_decode($response2->getBody()->getContents(), true);
        // $response->assertJson($list);
    }

    // 删除收货地址
    public function testDelete()
    {
        $address = Address::query()->first();
        $this->assertNotEmpty($address);
        $response = $this->post('wx/address/delete', [
            'id' => $address->id,
        ], $this->getAuthHeader());
        $response->assertJson(['errno' => 0]);
        $address = Address::query()->find($address->id);
        $this->assertEmpty($address);
    }

    // 测试用户详情地址
    public function testDetail()
    {
        $response = $this->get('wx/address/detail?id=1', $this->getAuthHeader());
        $response->assertJson(['errno' => 0]);
        $client = new Client();
        $response2 = $client->get('http://localhost:8080/wx/address/detail?id=1',
            ['headers' => ['X-Litemall-Token' => $this->token]]);
        $list = json_decode($response2->getBody()->getContents(), true);
        $response->assertJson($list);
    }
}