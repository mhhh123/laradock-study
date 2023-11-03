<?php
// +----------------------------------------------------------------------
// | User: zq
// +----------------------------------------------------------------------
// | Time: 2021/12/14 09:04
// +----------------------------------------------------------------------

namespace Tests\Unit;

use App\Enums\OrderEnums;
use App\Inputs\OrderSubmitInput;
use App\Jobs\OrderUnpaidTimeEndJob;
use App\Models\Goods\GoodsProduct;
use App\Models\Order\Order;
use App\Models\Order\OrderGoods;
use App\Models\Promotion\GrouponRules;
use App\Models\User\User;
use App\Services\Goods\GoodsServices;
use App\Services\Order\CartServices;
use App\Services\Order\ExpressServices;
use App\Services\Order\OrderServices;
use App\Services\User\AddressServices;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use DatabaseTransactions;

    public function testReduceStock()
    {
        /** @var GoodsProduct $prodcut1 */
        $prodcut1 = factory(GoodsProduct::class)->create(['price' => 11.3]);
        /** @var GoodsProduct $prodcut2 */
        $prodcut2 = factory(GoodsProduct::class)->state('groupon')->create(['price' => 20.56]);
        /** @var GoodsProduct $prodcut3 */
        $prodcut3 = factory(GoodsProduct::class)->create(['price' => 10.6]);
        CartServices::getInstance()->add($this->user->id, $prodcut1->goods_id, $prodcut1->id, 2);
        CartServices::getInstance()->add($this->user->id, $prodcut2->goods_id, $prodcut2->id, 5);
        CartServices::getInstance()->add($this->user->id, $prodcut3->goods_id, $prodcut3->id, 3);
        // 取消商品选中
        CartServices::getInstance()->updateChecked($this->user->id, [$prodcut1->id], false);
        // 11.3*2 + (20.56-1) = 42.16
        $checkedGoodsList = CartServices::getInstance()->getCheckedCartlist($this->user->id);

        OrderServices::getInstance()->reduceProductsStock($checkedGoodsList);

        $this->assertEquals($prodcut2->number - 5, $prodcut2->refresh()->number);
        $this->assertEquals($prodcut3->number - 3, $prodcut3->refresh()->number);
    }

    public function testSubmit()
    {
        $this->user = factory(User::class)->state('address_default')->create();
        $address = AddressService::getInstance()->getDefaultAddress($this->user->id);

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

        $input = OrderSubmitInput::new([
            'cartId' => 0,
            'addressId' => $address->id,
            'couponId' => 0,
            'grouponRulesId' => $rulesId,
            'message' => '备注'
        ]);
        $order = OrderService::getInstance()->submit($this->user->id, $input);

        $this->assertNotEmpty($order->id);
        $this->assertEquals($checkedGoodsPrice, $order->goods_price);
        $this->assertEquals($checkedGoodsPrice, $order->actual_price);
        $this->assertEquals($checkedGoodsPrice, $order->order_price);
        $this->assertEquals($grouponPrice, $order->groupon_price);
        $this->assertEquals('备注', $order->message);

        $list = OrderGoods::whereOrderId($order->id)->get()->toArray();
        $this->assertEquals(2, count($list));

        $prodcutIds = CartService::getInstance()->getCartList($this->user->id)->pluck('product_id')->toArray();
        $this->assertEquals([$prodcut1->id], $prodcutIds);
    }

    public function testJob()
    {
        dispatch(new OrderUnpaidTimeEndJob(1, 2));
    }

    private function getOrder()
    {
        $this->user = factory(User::class)->state('address_default')->create();
        $address = AddressService::getInstance()->getDefaultAddress($this->user->id);

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

        $input = OrderSubmitInput::new([
            'cartId' => 0,
            'addressId' => $address->id,
            'couponId' => 0,
            'grouponRulesId' => $rulesId,
            'message' => '备注'
        ]);
        $order = OrderService::getInstance()->submit($this->user->id, $input);
        return $order;
    }

    public function testCancel()
    {
        $order = $this->getOrder();

        OrderService::getInstance()->userCancel($this->user->id, $order->id);
        // 获取订单状态
        $this->assertEquals(OrderEnums::STATUS_CANCEL, $order->refresh()->order_status);

        // 验证库存
        $goodsList = OrderService::getInstance()->getOrderGoodsList($order->id);
        $productIds = $goodsList->pluck('product_id')->toArray();
        $products = GoodsService::getInstance()->getGoodsProductByIds($productIds);
        $this->assertEquals([100, 100], $products->pluck('number')->toArray());
    }

    public function testCas()
    {
        $user = User::first(['id', 'nickname', 'mobile', 'update_time']);
        $user->nickname = 'test';
        $user->mobile = 15000000000;
        User::whereId($user->id)->update(['mobile' => '15000000001']);
        $ret = $user->cas();
        dd($ret, $user);
    }

    // 基础流程
    public function testBaseProcess()
    {
        // 支付完成
        $order = $this->getOrder()->refresh();
        OrderService::getInstance()->payOrder($order, 'payid_test');
        $this->assertEquals(OrderEnums::STATUS_PAY, $order->refresh()->order_status);
        $this->assertEquals('payid_test', $order->pay_id);

        // 发货
        $shipSn = '123456';
        $shipChannel = 'shunfeng';
        OrderService::getInstance()->ship($this->user->id, $order->id, $shipSn, $shipChannel);
        // 刷新订单
        $order->refresh();
        // 验证订单状态,应该是已发货
        $this->assertEquals(OrderEnums::STATUS_SHIP, $order->order_status);
        // 验证物流编号
        $this->assertEquals($shipSn, $order->ship_sn);
        // 验证物流公司
        $this->assertEquals($shipChannel, $order->ship_channel);

        // 确认收货
        OrderService::getInstance()->confirm($this->user->id, $order->id);
        // 刷新订单
        $order->refresh();
        // 验证待评价商品数量
        $this->assertEquals(2, $order->comments);
        // 验证订单状态,应该是已收货
        $this->assertEquals(OrderEnums::STATUS_CONFIRM, $order->order_status);

        // 删除订单
        OrderService::getInstance()->delete($this->user->id, $order->id);
        // 验证订单状态,应该是空的,拿不到了
        $this->assertNull(Order::query()->find($order->id));
    }

    // 退款流程
    public function testRefundProcess()
    {
        // 支付完成
        $order = $this->getOrder()->refresh();
        OrderService::getInstance()->payOrder($order, 'payid_test');
        $this->assertEquals(OrderEnums::STATUS_PAY, $order->refresh()->order_status);
        $this->assertEquals('payid_test', $order->pay_id);

        // 申请退款
        OrderService::getInstance()->refund($this->user->id, $order->id);
        // 刷新订单
        $order->refresh();
        // 验证状态, 应该是 202 待退款
        $this->assertEquals(OrderEnums::STATUS_REFUND, $order->order_status);

        // 同意退款
        OrderService::getInstance()->agreeRefund($order->refresh(), '微信退款接口', '1234567');
        // 刷新订单
        $order->refresh();
        // 验证状态, 应该是 203 已完成退款
        $this->assertEquals(OrderEnums::STATUS_REFUND_CONFIRM, $order->order_status);
        $this->assertEquals('微信退款接口', $order->refund_type);
        $this->assertEquals('1234567', $order->refund_content);

        // 删除订单
        OrderService::getInstance()->delete($this->user->id, $order->id);
        // 验证订单状态,应该是空的,拿不到了
        $this->assertNull(Order::query()->find($order->id));
    }

    // 测试支付回调
    public function testPayOrder()
    {
        $order = $this->getOrder()->refresh();
        OrderService::getInstance()->payOrder($order, 'payid_test');
        dd($order->refresh()->toArray());
    }

    public function testExpress()
    {
        ExpressServices::getInstance()->getOrderTraces('YTO', '12345678');
    }

    public function testOrderStatusTrait()
    {
        $order = $this->getOrder();
        $this->assertEquals(true, $order->isCreateStatus());
        $this->assertEquals(false, $order->isCancelStatus());
        $this->assertEquals(false, $order->isPayStatus());
        $this->assertEquals(true, $order->canCancelHandle());
        $this->assertEquals(true, $order->canPayHandle());
        $this->assertEquals(false, $order->canDeleteHandle());
        $this->assertEquals(false, $order->canConfirmHandle());
    }

}
