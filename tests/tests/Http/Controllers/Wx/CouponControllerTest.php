<?php
// +----------------------------------------------------------------------
// | User: zq
// +----------------------------------------------------------------------
// | Time: 2021/12/13 14:14
// +----------------------------------------------------------------------

namespace Tests\Http\Controllers\Wx;

use Tests\TestCase;

class CouponControllerTest extends TestCase
{

    public function testList()
    {
        $this->assertLitemallApiGet('wx/coupon/list');
    }

    public function testMylist()
    {
        $this->assertLitemallApiGet('wx/coupon/mylist');
        $this->assertLitemallApiGet('wx/coupon/mylist?status=0');
        $this->assertLitemallApiGet('wx/coupon/mylist?status=1');
        $this->assertLitemallApiGet('wx/coupon/mylist?status=2');
    }
}