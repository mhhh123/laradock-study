<?php
// +----------------------------------------------------------------------
// | User: zq
// +----------------------------------------------------------------------
// | Time: 2021/12/10 15:16
// +----------------------------------------------------------------------

namespace Tests\Http\Controllers\Wx;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class GoodsControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function testCount()
    {
        $this->assertLitemallApiGet('wx/goods/count');
    }

    public function testCategory()
    {
        $this->assertLitemallApiGet('wx/goods/category?id=1008009');
        $this->assertLitemallApiGet('wx/goods/category?id=1005000');
    }

    public function testList()
    {
        $this->assertLitemallApiGet('wx/goods/list?categoryId=abc');
        $this->assertLitemallApiGet('wx/goods/list?isNew=0');
        $this->assertLitemallApiGet('wx/goods/list?isNew=a');
        $this->assertLitemallApiGet('wx/goods/list?page=a&limit=5');
        $this->assertLitemallApiGet('wx/goods/list?page=1&limit=a');
        $this->assertLitemallApiGet('wx/goods/list?sort=name&order=asc');
        $this->assertLitemallApiGet('wx/goods/list?sort=id&order=asc', ['errmsg']);
        $this->assertLitemallApiGet('wx/goods/list?sort=name&order=asc', ['errmsg']);
        $this->assertLitemallApiGet('wx/goods/list');
        $this->assertLitemallApiGet('wx/goods/list?categoryId=1008009');
        $this->assertLitemallApiGet('wx/goods/list?categoryId=abc');
        $this->assertLitemallApiGet('wx/goods/list?brandId=1001000');
        $this->assertLitemallApiGet('wx/goods/list?keyword=4');
        $this->assertLitemallApiGet('wx/goods/list?isNew=0');
        $this->assertLitemallApiGet('wx/goods/list?isHot=1');
        $this->assertLitemallApiGet('wx/goods/list?page=2&limit=5');
    }

    public function testDetail()
    {
        $this->assertLitemallApiGet('wx/goods/detail?id=1009009');
        $this->assertLitemallApiGet('wx/goods/detail?id=1181000');
    }
}