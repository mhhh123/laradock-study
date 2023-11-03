<?php
// +----------------------------------------------------------------------
// | User: zq
// +----------------------------------------------------------------------
// | Time: 2021/12/15 15:04
// +----------------------------------------------------------------------

namespace Tests\Http\Controllers\Wx;

use App\Models\Test\Test1;
use Tests\TestCase;

class GrouponControllerTest extends TestCase
{

    public function testList()
    {
        $this->assertLitemallApiGet('wx/groupon/list');
    }

    // 测试数据
    public function testTest()
    {
        $test = factory(Test1::class, 10)->create();
        dd();
    }
}