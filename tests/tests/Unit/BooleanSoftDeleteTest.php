<?php

namespace Tests\Unit;

use App\Models\Goods\Goods;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class BooleanSoftDeleteTest extends TestCase
{
    // 为了防止脏数据,不会真的写入数据
    use DatabaseTransactions;

    private $goodsId;

    // 用例启动时执行
    protected function setUp(): void
    {
        parent::setUp();
        $this->goodsId = Goods::query()->insertGetId([
            "goods_sn" => "test",
            "name" => "轻奢纯棉刺绣水洗四件套",
            "category_id" => 1008009,
            "brand_id" => 0,
            "gallery" => '',
            "keywords" => "",
            "brief" => "设计师原款，精致绣花",
            "is_on_sale" => 1,
            "sort_order" => 23,
            "pic_url" => "https://yanxuan.nosdn.127.net/8ab2d3287af0cefa2cc539e40600621d.png",
            "share_url" => "",
            "is_new" => 0,
            "is_hot" => 0,
            "unit" => "件",
            "counter_price" => 919.0,
            "retail_price" => 899.0,
            "detail" => '',
        ]);
    }

    // 用例执行完之后执行
    protected function tearDown(): void
    {
        parent::tearDown(); // TODO: Change the autogenerated stub
    }

    public function testSoftDeleteByModel()
    {
        $goods = Goods::query()->whereId($this->goodsId)->first();
        $goods->delete();
        $this->assertTrue($goods->deleted);
        $goods = Goods::query()->whereId($this->goodsId)->first();
        $this->assertNull($goods);

        $goods = Goods::onlyTrashed()->whereId($this->goodsId)->first();
        $goods->restore();
        $this->assertFalse($goods->deleted);
        $goods = Goods::query()->whereId($this->goodsId)->first();
        $this->assertEquals($this->goodsId, $goods->id ?? 0);
    }

    public function testSoftDeleteByBuilder()
    {
        // 正常查询
        $goods = Goods::query()->whereId($this->goodsId)->first();
        $this->assertEquals($this->goodsId, $goods->id ?? 0);

        Goods::withoutTrashed()->whereId($this->goodsId)->first();
        $this->assertEquals($this->goodsId, $goods->id ?? 0);

        // 删除
        $ret = Goods::query()->whereId($this->goodsId)->delete();
        $this->assertEquals(1, $ret);
        $goods = Goods::query()->whereId($this->goodsId)->first();
        $this->assertNull($goods);

        $goods = Goods::withTrashed()->whereId($this->goodsId)->first();
        $this->assertEquals($this->goodsId, $goods->id ?? 0);

        $goods = Goods::onlyTrashed()->whereId($this->goodsId)->first();
        $this->assertEquals($this->goodsId, $goods->id ?? 0);

        // 恢复
        $ret = Goods::withTrashed()->whereId($this->goodsId)->restore();
        $this->assertEquals(1, $ret);
        $goods = Goods::onlyTrashed()->whereId($this->goodsId)->first();
        $this->assertNull($goods);
    }
}