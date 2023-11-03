<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\BaseModel
 *
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel query()
 * @mixin \Eloquent
 */
	class BaseModel extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Collect
 *
 * @property int $id
 * @property int $user_id 用户表的用户ID
 * @property int $value_id 如果type=0，则是商品ID；如果type=1，则是专题ID
 * @property int $type 收藏类型，如果type=0，则是商品ID；如果type=1，则是专题ID
 * @property string|null $add_time 创建时间
 * @property string|null $update_time 更新时间
 * @property bool|null $deleted 逻辑删除
 * @method static \Illuminate\Database\Eloquent\Builder|Collect newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Collect newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Collect query()
 * @method static \Illuminate\Database\Eloquent\Builder|Collect whereAddTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collect whereDeleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collect whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collect whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collect whereUpdateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collect whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collect whereValueId($value)
 * @mixin \Eloquent
 */
	class Collect extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Comment
 *
 * @property int $id
 * @property int $value_id 如果type=0，则是商品评论；如果是type=1，则是专题评论。
 * @property int $type 评论类型，如果type=0，则是商品评论；如果是type=1，则是专题评论；
 * @property string|null $content 评论内容
 * @property string|null $admin_content 管理员回复内容
 * @property int $user_id 用户表的用户ID
 * @property int|null $has_picture 是否含有图片
 * @property string|null $pic_urls 图片地址列表，采用JSON数组格式
 * @property int|null $star 评分， 1-5
 * @property \Illuminate\Support\Carbon|null $add_time 创建时间
 * @property \Illuminate\Support\Carbon|null $update_time 更新时间
 * @property bool|null $deleted 逻辑删除
 * @method static \Illuminate\Database\Eloquent\Builder|Comment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment query()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereAddTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereAdminContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereDeleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereHasPicture($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment wherePicUrls($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereStar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereUpdateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereValueId($value)
 * @mixin \Eloquent
 */
	class Comment extends \Eloquent {}
}

namespace App\Models\Goods{
/**
 * App\Models\Goods\Brand
 *
 * @property int $id
 * @property string $name 品牌商名称
 * @property string $desc 品牌商简介
 * @property string $pic_url 品牌商页的品牌商图片
 * @property int|null $sort_order
 * @property float|null $floor_price 品牌商的商品低价，仅用于页面展示
 * @property \Illuminate\Support\Carbon|null $add_time 创建时间
 * @property \Illuminate\Support\Carbon|null $update_time 更新时间
 * @property bool|null $deleted 逻辑删除
 * @method static \Illuminate\Database\Eloquent\Builder|Brand newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Brand newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Brand query()
 * @method static \Illuminate\Database\Eloquent\Builder|Brand whereAddTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Brand whereDeleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Brand whereDesc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Brand whereFloorPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Brand whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Brand whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Brand wherePicUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Brand whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Brand whereUpdateTime($value)
 * @mixin \Eloquent
 */
	class Brand extends \Eloquent {}
}

namespace App\Models\Goods{
/**
 * App\Models\Goods\Category
 *
 * @property int $id
 * @property string $name 类目名称
 * @property string $keywords 类目关键字，以JSON数组格式
 * @property string|null $desc 类目广告语介绍
 * @property int $pid 父类目ID
 * @property string|null $icon_url 类目图标
 * @property string|null $pic_url 类目图片
 * @property string|null $level
 * @property int|null $sort_order 排序
 * @property \Illuminate\Support\Carbon|null $add_time 创建时间
 * @property \Illuminate\Support\Carbon|null $update_time 更新时间
 * @property bool|null $deleted 逻辑删除
 * @method static \Illuminate\Database\Eloquent\Builder|Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category query()
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereAddTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereDeleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereDesc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereIconUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereKeywords($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category wherePicUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category wherePid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereUpdateTime($value)
 * @mixin \Eloquent
 */
	class Category extends \Eloquent {}
}

namespace App\Models\Goods{
/**
 * App\Models\Goods\Footprint
 *
 * @property int $id
 * @property int $user_id 用户表的用户ID
 * @property int $goods_id 浏览商品ID
 * @property \Illuminate\Support\Carbon|null $add_time 创建时间
 * @property \Illuminate\Support\Carbon|null $update_time 更新时间
 * @property bool|null $deleted 逻辑删除
 * @method static \Illuminate\Database\Eloquent\Builder|Footprint newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Footprint newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Footprint query()
 * @method static \Illuminate\Database\Eloquent\Builder|Footprint whereAddTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Footprint whereDeleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Footprint whereGoodsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Footprint whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Footprint whereUpdateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Footprint whereUserId($value)
 * @mixin \Eloquent
 */
	class Footprint extends \Eloquent {}
}

namespace App\Models\Goods{
/**
 * App\Models\Goods\GoodsAttribute
 *
 * @method static \Illuminate\Database\Eloquent\Builder|GoodsAttribute newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GoodsAttribute newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GoodsAttribute query()
 * @mixin \Eloquent
 */
	class GoodAttribute extends \Eloquent {}
}

namespace App\Models\Goods{
/**
 * App\Models\Goods\Goods
 *
 * @property int $id
 * @property string $goods_sn 商品编号
 * @property string $name 商品名称
 * @property int|null $category_id 商品所属类目ID
 * @property int|null $brand_id
 * @property array|null $gallery 商品宣传图片列表，采用JSON数组格式
 * @property string|null $keywords 商品关键字，采用逗号间隔
 * @property string|null $brief 商品简介
 * @property bool|null $is_on_sale 是否上架
 * @property int|null $sort_order
 * @property string|null $pic_url 商品页面商品图片
 * @property string|null $share_url 商品分享海报
 * @property bool|null $is_new 是否新品首发，如果设置则可以在新品首发页面展示
 * @property bool|null $is_hot 是否人气推荐，如果设置则可以在人气推荐页面展示
 * @property string|null $unit 商品单位，例如件、盒
 * @property float|null $counter_price 专柜价格
 * @property float|null $retail_price 零售价格
 * @property string|null $detail 商品详细介绍，是富文本格式
 * @property \Illuminate\Support\Carbon|null $add_time 创建时间
 * @property \Illuminate\Support\Carbon|null $update_time 更新时间
 * @property bool|null $deleted 逻辑删除
 * @method static \Illuminate\Database\Eloquent\Builder|Goods newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Goods newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Goods query()
 * @method static \Illuminate\Database\Eloquent\Builder|Goods whereAddTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Goods whereBrandId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Goods whereBrief($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Goods whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Goods whereCounterPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Goods whereDeleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Goods whereDetail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Goods whereGallery($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Goods whereGoodsSn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Goods whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Goods whereIsHot($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Goods whereIsNew($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Goods whereIsOnSale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Goods whereKeywords($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Goods whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Goods wherePicUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Goods whereRetailPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Goods whereShareUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Goods whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Goods whereUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Goods whereUpdateTime($value)
 * @mixin \Eloquent
 */
	class Goods extends \Eloquent {}
}

namespace App\Models\Goods{
/**
 * App\Models\Goods\GoodsProduct
 *
 * @method static \Illuminate\Database\Eloquent\Builder|GoodsProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GoodsProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GoodsProduct query()
 * @mixin \Eloquent
 */
	class GoodsProduct extends \Eloquent {}
}

namespace App\Models\Goods{
/**
 * App\Models\Goods\GoodsSpecification
 *
 * @property int $id
 * @property int $goods_id 商品表的商品ID
 * @property string $specification 商品规格名称
 * @property string $value 商品规格值
 * @property string $pic_url 商品规格图片
 * @property \Illuminate\Support\Carbon|null $add_time 创建时间
 * @property \Illuminate\Support\Carbon|null $update_time 更新时间
 * @property bool|null $deleted 逻辑删除
 * @method static \Illuminate\Database\Eloquent\Builder|GoodsSpecification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GoodsSpecification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GoodsSpecification query()
 * @method static \Illuminate\Database\Eloquent\Builder|GoodsSpecification whereAddTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GoodsSpecification whereDeleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GoodsSpecification whereGoodsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GoodsSpecification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GoodsSpecification wherePicUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GoodsSpecification whereSpecification($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GoodsSpecification whereUpdateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GoodsSpecification whereValue($value)
 * @mixin \Eloquent
 */
	class GoodsSpecification extends \Eloquent {}
}

namespace App\Models\Goods{
/**
 * App\Models\Goods\issue
 *
 * @property int $id
 * @property string|null $question 问题标题
 * @property string|null $answer 问题答案
 * @property \Illuminate\Support\Carbon|null $add_time 创建时间
 * @property \Illuminate\Support\Carbon|null $update_time 更新时间
 * @property bool|null $deleted 逻辑删除
 * @method static \Illuminate\Database\Eloquent\Builder|issue newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|issue newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|issue query()
 * @method static \Illuminate\Database\Eloquent\Builder|issue whereAddTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|issue whereAnswer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|issue whereDeleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|issue whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|issue whereQuestion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|issue whereUpdateTime($value)
 * @mixin \Eloquent
 */
	class issue extends \Eloquent {}
}

namespace App\Models\Order{
/**
 * App\Models\Order\Cart
 *
 * @property int $id
 * @property int|null $user_id 用户表的用户ID
 * @property int|null $goods_id 商品表的商品ID
 * @property string|null $goods_sn 商品编号
 * @property string|null $goods_name 商品名称
 * @property int|null $product_id 商品货品表的货品ID
 * @property string|null $price 商品货品的价格
 * @property int|null $number 商品货品的数量
 * @property array|null $specifications 商品规格值列表，采用JSON数组格式
 * @property bool|null $checked 购物车中商品是否选择状态
 * @property string|null $pic_url 商品图片或者商品货品图片
 * @property \Illuminate\Support\Carbon|null $add_time 创建时间
 * @property \Illuminate\Support\Carbon|null $update_time 更新时间
 * @property bool|null $deleted 逻辑删除
 * @method static \Illuminate\Database\Eloquent\Builder|Cart newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Cart newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Cart query()
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereAddTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereChecked($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereDeleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereGoodsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereGoodsName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereGoodsSn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart wherePicUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereSpecifications($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereUpdateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereUserId($value)
 * @mixin \Eloquent
 */
	class Cart extends \Eloquent {}
}

namespace App\Models\Order{
/**
 * App\Models\Order\Order
 *
 * @property int $id
 * @property int $user_id 用户表的用户ID
 * @property string $order_sn 订单编号
 * @property int $order_status 订单状态
 * @property int|null $aftersale_status 售后状态，0是可申请，1是用户已申请，2是管理员审核通过，3是管理员退款成功，4是管理员审核拒绝，5是用户已取消
 * @property string $consignee 收货人名称
 * @property string $mobile 收货人手机号
 * @property string $address 收货具体地址
 * @property string $message 用户订单留言
 * @property string $goods_price 商品总费用
 * @property string $freight_price 配送费用
 * @property string $coupon_price 优惠券减免
 * @property string $integral_price 用户积分减免
 * @property string $groupon_price 团购优惠价减免
 * @property string $order_price 订单费用， = goods_price + freight_price - coupon_price
 * @property string $actual_price 实付费用， = order_price - integral_price
 * @property string|null $pay_id 微信付款编号
 * @property string|null $pay_time 微信付款时间
 * @property string|null $ship_sn 发货编号
 * @property string|null $ship_channel 发货快递公司
 * @property string|null $ship_time 发货开始时间
 * @property string|null $refund_amount 实际退款金额，（有可能退款金额小于实际支付金额）
 * @property string|null $refund_type 退款方式
 * @property string|null $refund_content 退款备注
 * @property string|null $refund_time 退款时间
 * @property string|null $confirm_time 用户确认收货时间
 * @property int|null $comments 待评价订单商品数量
 * @property string|null $end_time 订单关闭时间
 * @property \Illuminate\Support\Carbon|null $add_time 创建时间
 * @property \Illuminate\Support\Carbon|null $update_time 更新时间
 * @property bool|null $deleted 逻辑删除
 * @method static \Illuminate\Database\Eloquent\Builder|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order query()
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereActualPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereAddTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereAftersaleStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereComments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereConfirmTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereConsignee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCouponPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDeleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereFreightPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereGoodsPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereGrouponPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereIntegralPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereOrderPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereOrderSn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereOrderStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePayId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePayTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereRefundAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereRefundContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereRefundTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereRefundType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereShipChannel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereShipSn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereShipTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUpdateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUserId($value)
 * @mixin \Eloquent
 */
	class Order extends \Eloquent {}
}

namespace App\Models\Order{
/**
 * App\Models\Order\OrderGoods
 *
 * @property int $id
 * @property int $order_id 订单表的订单ID
 * @property int $goods_id 商品表的商品ID
 * @property string $goods_name 商品名称
 * @property string $goods_sn 商品编号
 * @property int $product_id 商品货品表的货品ID
 * @property int $number 商品货品的购买数量
 * @property string $price 商品货品的售价
 * @property string $specifications 商品货品的规格列表
 * @property string $pic_url 商品货品图片或者商品图片
 * @property int|null $comment 订单商品评论，如果是-1，则超期不能评价；如果是0，则可以评价；如果其他值，则是comment表里面的评论ID。
 * @property \Illuminate\Support\Carbon|null $add_time 创建时间
 * @property \Illuminate\Support\Carbon|null $update_time 更新时间
 * @property bool|null $deleted 逻辑删除
 * @method static \Illuminate\Database\Eloquent\Builder|OrderGoods newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderGoods newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderGoods query()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderGoods whereAddTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderGoods whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderGoods whereDeleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderGoods whereGoodsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderGoods whereGoodsName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderGoods whereGoodsSn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderGoods whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderGoods whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderGoods whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderGoods wherePicUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderGoods wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderGoods whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderGoods whereSpecifications($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderGoods whereUpdateTime($value)
 * @mixin \Eloquent
 */
	class OrderGoods extends \Eloquent {}
}

namespace App\Models\Promotion{
/**
 * App\Models\Promotion\Coupon
 *
 * @property int $id
 * @property string $name 优惠券名称
 * @property string|null $desc 优惠券介绍，通常是显示优惠券使用限制文字
 * @property string|null $tag 优惠券标签，例如新人专用
 * @property int $total 优惠券数量，如果是0，则是无限量
 * @property float|null $discount 优惠金额，
 * @property float|null $min 最少消费金额才能使用优惠券。
 * @property int|null $limit 用户领券限制数量，如果是0，则是不限制；默认是1，限领一张.
 * @property int|null $type 优惠券赠送类型，如果是0则通用券，用户领取；如果是1，则是注册赠券；如果是2，则是优惠券码兑换；
 * @property int|null $status 优惠券状态，如果是0则是正常可用；如果是1则是过期; 如果是2则是下架。
 * @property int|null $goods_type 商品限制类型，如果0则全商品，如果是1则是类目限制，如果是2则是商品限制。
 * @property string|null $goods_value 商品限制值，goods_type如果是0则空集合，如果是1则是类目集合，如果是2则是商品集合。
 * @property string|null $code 优惠券兑换码
 * @property int|null $time_type 有效时间限制，如果是0，则基于领取时间的有效天数days；如果是1，则start_time和end_time是优惠券有效期；
 * @property int|null $days 基于领取时间的有效天数days。
 * @property string|null $start_time 使用券开始时间
 * @property string|null $end_time 使用券截至时间
 * @property \Illuminate\Support\Carbon|null $add_time 创建时间
 * @property \Illuminate\Support\Carbon|null $update_time 更新时间
 * @property bool|null $deleted 逻辑删除
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon query()
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereAddTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereDeleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereDesc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereGoodsType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereGoodsValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereLimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereMin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereTag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereTimeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereUpdateTime($value)
 * @mixin \Eloquent
 */
	class Coupon extends \Eloquent {}
}

namespace App\Models\Promotion{
/**
 * App\Models\Promotion\CouponUser
 *
 * @property int $id
 * @property int $user_id 用户ID
 * @property int $coupon_id 优惠券ID
 * @property int|null $status 使用状态, 如果是0则未使用；如果是1则已使用；如果是2则已过期；如果是3则已经下架；
 * @property string|null $used_time 使用时间
 * @property string|null $start_time 有效期开始时间
 * @property string|null $end_time 有效期截至时间
 * @property int|null $order_id 订单ID
 * @property \Illuminate\Support\Carbon|null $add_time 创建时间
 * @property \Illuminate\Support\Carbon|null $update_time 更新时间
 * @property bool|null $deleted 逻辑删除
 * @method static \Illuminate\Database\Eloquent\Builder|CouponUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CouponUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CouponUser query()
 * @method static \Illuminate\Database\Eloquent\Builder|CouponUser whereAddTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CouponUser whereCouponId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CouponUser whereDeleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CouponUser whereEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CouponUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CouponUser whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CouponUser whereStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CouponUser whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CouponUser whereUpdateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CouponUser whereUsedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CouponUser whereUserId($value)
 * @mixin \Eloquent
 */
	class CouponUser extends \Eloquent {}
}

namespace App\Models\Promotion{
/**
 * App\Models\Promotion\Groupon
 *
 * @property int $id
 * @property int $order_id 关联的订单ID
 * @property int|null $groupon_id 如果是开团用户，则groupon_id是0；如果是参团用户，则groupon_id是团购活动ID
 * @property int $rules_id 团购规则ID，关联litemall_groupon_rules表ID字段
 * @property int $user_id 用户ID
 * @property string|null $share_url 团购分享图片地址
 * @property int $creator_user_id 开团用户ID
 * @property string|null $creator_user_time 开团时间
 * @property int|null $status 团购活动状态，开团未支付则0，开团中则1，开团失败则2
 * @property \Illuminate\Support\Carbon $add_time 创建时间
 * @property \Illuminate\Support\Carbon|null $update_time 更新时间
 * @property bool|null $deleted 逻辑删除
 * @method static \Illuminate\Database\Eloquent\Builder|Groupon newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Groupon newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Groupon query()
 * @method static \Illuminate\Database\Eloquent\Builder|Groupon whereAddTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Groupon whereCreatorUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Groupon whereCreatorUserTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Groupon whereDeleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Groupon whereGrouponId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Groupon whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Groupon whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Groupon whereRulesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Groupon whereShareUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Groupon whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Groupon whereUpdateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Groupon whereUserId($value)
 * @mixin \Eloquent
 */
	class Groupon extends \Eloquent {}
}

namespace App\Models\Promotion{
/**
 * App\Models\Promotion\GrouponRules
 *
 * @property int $id
 * @property int $goods_id 商品表的商品ID
 * @property string $goods_name 商品名称
 * @property string|null $pic_url 商品图片或者商品货品图片
 * @property float $discount 优惠金额
 * @property int $discount_member 达到优惠条件的人数
 * @property string|null $expire_time 团购过期时间
 * @property int|null $status 团购规则状态，正常上线则0，到期自动下线则1，管理手动下线则2
 * @property \Illuminate\Support\Carbon $add_time 创建时间
 * @property \Illuminate\Support\Carbon|null $update_time 更新时间
 * @property bool|null $deleted 逻辑删除
 * @method static \Illuminate\Database\Eloquent\Builder|GrouponRules newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GrouponRules newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GrouponRules query()
 * @method static \Illuminate\Database\Eloquent\Builder|GrouponRules whereAddTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GrouponRules whereDeleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GrouponRules whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GrouponRules whereDiscountMember($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GrouponRules whereExpireTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GrouponRules whereGoodsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GrouponRules whereGoodsName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GrouponRules whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GrouponRules wherePicUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GrouponRules whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GrouponRules whereUpdateTime($value)
 * @mixin \Eloquent
 */
	class GrouponRules extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\SearchHistory
 *
 * @property int $id
 * @property int $user_id 用户表的用户ID
 * @property string $keyword 搜索关键字
 * @property string $from 搜索来源，如pc、wx、app
 * @property \Illuminate\Support\Carbon|null $add_time 创建时间
 * @property \Illuminate\Support\Carbon|null $update_time 更新时间
 * @property bool|null $deleted 逻辑删除
 * @method static \Illuminate\Database\Eloquent\Builder|SearchHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SearchHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SearchHistory query()
 * @method static \Illuminate\Database\Eloquent\Builder|SearchHistory whereAddTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SearchHistory whereDeleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SearchHistory whereFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SearchHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SearchHistory whereKeyword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SearchHistory whereUpdateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SearchHistory whereUserId($value)
 * @mixin \Eloquent
 */
	class SearchHistory extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\System
 *
 * @property int $id
 * @property string $key_name 系统配置名
 * @property string $key_value 系统配置值
 * @property \Illuminate\Support\Carbon|null $add_time 创建时间
 * @property \Illuminate\Support\Carbon|null $update_time 更新时间
 * @property bool|null $deleted 逻辑删除
 * @method static \Illuminate\Database\Eloquent\Builder|System newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|System newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|System query()
 * @method static \Illuminate\Database\Eloquent\Builder|System whereAddTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|System whereDeleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|System whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|System whereKeyName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|System whereKeyValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|System whereUpdateTime($value)
 * @mixin \Eloquent
 */
	class System extends \Eloquent {}
}

namespace App\Models\User{
/**
 * App\Models\User\Address
 *
 * @property int $id
 * @property string $name 收货人名称
 * @property int $user_id 用户表的用户ID
 * @property string $province 行政区域表的省ID
 * @property string $city 行政区域表的市ID
 * @property string $county 行政区域表的区县ID
 * @property string $address_detail 详细收货地址
 * @property string|null $area_code 地区编码
 * @property string|null $postal_code 邮政编码
 * @property string $tel 手机号码
 * @property int $is_default 是否默认地址
 * @property \Illuminate\Support\Carbon|null $add_time 创建时间
 * @property \Illuminate\Support\Carbon|null $update_time 更新时间
 * @property bool|null $deleted 逻辑删除
 * @method static \Illuminate\Database\Eloquent\Builder|Address newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Address newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Address query()
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereAddTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereAddressDetail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereAreaCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereCounty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereDeleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereIsDefault($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address wherePostalCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereProvince($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereTel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereUpdateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereUserId($value)
 * @mixin \Eloquent
 */
	class Address extends \Eloquent {}
}

namespace App\Models\User{
/**
 * App\Models\User\User
 *
 * @property int $id
 * @property string $username 用户名称
 * @property string $password 用户密码
 * @property int $gender 性别：0 未知， 1男， 1 女
 * @property string|null $birthday 生日
 * @property string|null $last_login_time 最近一次登录时间
 * @property string $last_login_ip 最近一次登录IP地址
 * @property int|null $user_level 0 普通用户，1 VIP用户，2 高级VIP用户
 * @property string $nickname 用户昵称或网络名称
 * @property string $mobile 用户手机号码
 * @property string $avatar 用户头像图片
 * @property string $weixin_openid 微信登录openid
 * @property string $session_key 微信登录会话KEY
 * @property int $status 0 可用, 1 禁用, 2 注销
 * @property string|null $add_time 创建时间
 * @property string|null $update_time 更新时间
 * @property int|null $deleted 逻辑删除
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAddTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereBirthday($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDeleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastLoginIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastLoginTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereNickname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereSessionKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUserLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereWeixinOpenid($value)
 * @mixin \Eloquent
 */
	class User extends \Eloquent implements \Tymon\JWTAuth\Contracts\JWTSubject {}
}

