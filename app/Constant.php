<?php
namespace App;

class Constant
{
    const SEARCH_HISTORY_FROM_WX = 'wx';
    const SEARCH_HISTORY_FROM_APP = 'app';
    const SEARCH_HISTORY_FROM_PC = 'pc';

    const Collect_TYPE_GOODS = 0;
    const Collect_TYPE_TOPIC = 1;
//评价类型
    const COMMENT_TYPE_GOODS = 0;
    const COMMENT_TYPE_TOPIC = 1;
//优惠卷类型
    const Type_COMMON = 0;
    const Type_REGISTER = 1;
    const Type_CODE = 2;
    //优惠劵商品限制
    const GOODS_TYPE_ALL = 0;
    const GOODS_TYPE_CATEGORY = 1;
    const GOODS_TYPE_ARRAY = 2;
    // 优惠卷时间类型
    const STATUS_NORMAL = 0;
    const STATUS_EXPIRED = 1;
    const STATUS_OUT = 2;

    const  GROUP_RULE_STATUS_ON = 0;
    const  GROUP_RULE_STATUS_DOWN_EXPIRE = 1;
    const  GROUP_RULE_STATUS_DOWN_ADMIN = 2;


    const  GROUP_STATUS_NONE = 0;
    const  GROUP_STATUS_ON = 1;
    const  GROUP_STATUS_SUCCEED = 2;
    const  GROUP_STATUS_FAIL = 3;

    const Coupon_STATUS_USABLE = 0;
    const Coupon_STATUS_USED = 1;
    const Coupon_STATUS_EXPIRED = 2;
    const Coupon_STATUS_OUT = 3;
    const TIME_TYPE_DAYS = 0;
    const TIME_TYPE_TIME = 1;

    const STATUS_CREATE = 101;
    const STATUS_PAY = 201;
    const STATUS_SHIP = 301;
    const STATUS_CONFIRM = 401;
    const STATUS_CANCEL = 102;
    const STATUS_AUTO_CANCEL = 103;
    const STATUS_ADMIN_CANCEL = 104;
    const STATUS_REFUND = 202;
    const STATUS_REFUND_CONFIRM = 203;
    const STATUS_GROUPON_TIMEOUT = 204;
    const STATUS_AUTO_CONFIRM = 402;


    const STATUS_TEXT_MAP = [
        self::STATUS_CREATE => '未付款',
        self::STATUS_CANCEL => "已取消",
        self::STATUS_AUTO_CANCEL => "已取消(系统)",
        self::STATUS_ADMIN_CANCEL => "已取消(管理员)",
        self::STATUS_PAY => "已付款",
        self::STATUS_REFUND => "订单取消，退款中",
        self::STATUS_REFUND_CONFIRM => "已退款",
        self::STATUS_GROUPON_TIMEOUT => "已超时团购",
        self::STATUS_SHIP => "已发货",
        self::STATUS_CONFIRM => "已收货",
        self::STATUS_AUTO_CONFIRM => "已收货(系统)",
    ];


    const SHOW_TYPE_ALL = 0;//全部订单
    const SHOW_TYPE_WAIT_PAY = 1;//待付款订单
    const SHOW_TYPE_WAIT_DELIVERY = 2;//待发货订单
    const SHOW_TYPE_WAIT_RECEIPT = 3;//待收货订单
    const SHOW_TYPE_WAIT_COMMENT = 4;//待评价订单


    const SHOW_TYPE_STATUS_MAP = [
        self::SHOW_TYPE_ALL => [],
        self::SHOW_TYPE_WAIT_PAY => [self::STATUS_CREATE],
        self::SHOW_TYPE_WAIT_DELIVERY => [self::STATUS_PAY],
        self::SHOW_TYPE_WAIT_RECEIPT => [self::STATUS_SHIP],
        self::SHOW_TYPE_WAIT_COMMENT => [self::STATUS_CONFIRM]
        ];


}



