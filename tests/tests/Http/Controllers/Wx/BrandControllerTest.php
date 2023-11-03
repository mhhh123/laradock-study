<?php
// +----------------------------------------------------------------------
// | User: zq
// +----------------------------------------------------------------------
// | Time: 2021/12/9 14:05
// +----------------------------------------------------------------------

namespace Tests\Http\Controllers\Wx;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class BrandControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function testDetail()
    {
        $this->assertLitemallApiGet('wx/brand/detail', ['errmsg']);
        $this->assertLitemallApiGet('wx/brand/detail?id=1001002');
    }

    public function testList()
    {
        $this->assertLitemallApiGet('wx/brand/list');
    }
}