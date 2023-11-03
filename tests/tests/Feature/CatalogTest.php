<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CatalogTest extends TestCase
{
    // 为了防止脏数据,不会真的写入数据
    use DatabaseTransactions;

    public function testIndex()
    {
        $this->assertLitemallApiGet('wx/catalog/index');
        $this->assertLitemallApiGet('wx/catalog/index?id=1005001');
        $this->assertLitemallApiGet('wx/catalog/index?id=10050011');
    }

    public function testCurrent()
    {
        $this->assertLitemallApiGet('wx/catalog/current', ['errmsg']);
        $this->assertLitemallApiGet('wx/catalog/current?id=1005000');
        $this->assertLitemallApiGet('wx/catalog/current?id=10050001');
    }
}