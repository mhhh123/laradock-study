<?php
// +----------------------------------------------------------------------
// | User: zq
// +----------------------------------------------------------------------
// | Time: 2021/12/14 09:04
// +----------------------------------------------------------------------

namespace Tests\Unit;

use App\Models\Goods\GoodsProduct;
use App\Models\Promotion\GrouponRules;
use App\Services\Order\CartService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CartTest extends TestCase
{
    use DatabaseTransactions;

    public function testGetCartPriceCutGrouponSimple()
    {
        /** @var GoodsProduct $prodcut1 */
        $prodcut1 = factory(GoodsProduct::class)->create(['price' => 11.3]);
        /** @var GoodsProduct $prodcut2 */
        $prodcut2 = factory(GoodsProduct::class)->create(['price' => 20.56]);
        /** @var GoodsProduct $prodcut3 */
        $prodcut3 = factory(GoodsProduct::class)->create(['price' => 10.6]);
        CartService::getInstance()->add($this->user->id, $prodcut1->goods_id, $prodcut1->id, 2);
        CartService::getInstance()->add($this->user->id, $prodcut2->goods_id, $prodcut2->id, 1);
        CartService::getInstance()->add($this->user->id, $prodcut3->goods_id, $prodcut3->id, 3);

        // 取消商品选中
        CartService::getInstance()->updateChecked($this->user->id, [$prodcut3->id], false);

        // 获取商品列表
        $checkedGoodsList = CartService::getInstance()->getCheckedCartlist($this->user->id);

        $grouponPrice = 0;
        $checkedGoodsPrice = CartService::getInstance()->getCartPriceCutGroupon($checkedGoodsList, null,
            $grouponPrice);

        $this->assertEquals(43.16, $checkedGoodsPrice);
    }

    public function testGetCartPriceCutGrouponGroupon()
    {
        /** @var GoodsProduct $prodcut1 */
        $prodcut1 = factory(GoodsProduct::class)->create(['price' => 11.3]);
        /** @var GoodsProduct $prodcut2 */
        $prodcut2 = factory(GoodsProduct::class)->state('groupon')->create(['price' => 20.56]);
        /** @var GoodsProduct $prodcut3 */
        $prodcut3 = factory(GoodsProduct::class)->create(['price' => 10.6]);
        CartService::getInstance()->add($this->user->id, $prodcut1->goods_id, $prodcut1->id, 2);
        CartService::getInstance()->add($this->user->id, $prodcut2->goods_id, $prodcut2->id, 5);
        CartService::getInstance()->add($this->user->id, $prodcut3->goods_id, $prodcut3->id, 3);

        // 取消商品选中
        CartService::getInstance()->updateChecked($this->user->id, [$prodcut1->id], false);

        // 11.3*2 + (20.56-1) = 42.16
        $checkedGoodsList = CartService::getInstance()->getCheckedCartlist($this->user->id);

        $grouponPrice = 0;
        $rulesId = GrouponRules::whereGoodsId($prodcut2->goods_id)->value('id') ?? null;
        $checkedGoodsPrice = CartService::getInstance()->getCartPriceCutGroupon($checkedGoodsList, $rulesId,
            $grouponPrice);

        $this->assertEquals(129.6, $checkedGoodsPrice);
    }
}