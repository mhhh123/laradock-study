<?php
// +----------------------------------------------------------------------
// | User: zq
// +----------------------------------------------------------------------
// | Time: 2021/12/21 13:26
// +----------------------------------------------------------------------

namespace Tests\Http\Controllers;

use Tests\TestCase;

class TestControllerTest extends TestCase
{

    public function testTest2()
    {
        $this->get('wx/test/test2');
    }

    public function testTest1()
    {
        $this->get('wx/test/test1');
    }
}