<?php
namespace App\Services;

use App\Models\System;

class SystemServices extends BaseServices
{
    const LITEMALL_WX_INDEX_NEW = "litemall_wx_index_new";
    const LITEMALL_WX_INDEX_HOT = "litemall_wx_index_hot";
    const LITEMALL_WX_INDEX_BRAND = "litemall_wx_index_brand";
    const LITEMALL_WX_INDEX_TOPIC = "litemall_wx_index_topic";
    const LITEMALL_WX_INDEX_CATLOG_LIST = "litemall_wx_catlog_list";
    const LITEMALL_WX_INDEX_CATLOG_GOODS = "litemall_wx_catlog_goods";
    const LITEMALL_WX_SHARE = "litemall_wx_share";
    // 运费相关配置
    const LITEMALL_EXPRESS_FREIGHT_VALUE = "litemall_express_freight_value";
    const LITEMALL_EXPRESS_FREIGHT_MIN = "litemall_express_freight_min";
    // 订单相关配置
    const LITEMALL_ORDER_UNPAID = "litemall_order_unpaid";
    const LITEMALL_ORDER_UNCONFIRM = "litemall_order_unconfirm";
    const LITEMALL_ORDER_COMMENT = "litemall_order_comment";
    // 商场相关配置
    const LITEMALL_MALL_NAME = "litemall_mall_name";
    const LITEMALL_MALL_ADDRESS = "litemall_mall_address";
    const LITEMALL_MALL_PHONE = "litemall_mall_phone";
    const LITEMALL_MALL_QQ = "litemall_mall_qq";
    const LITEMALL_MALL_LONGITUDE = "litemall_mall_longitude";
    const LITEMALL_MALL_Latitude = "litemall_mall_latitude";

    public function getTopicLimit() {
        return $this->get(self::LITEMALL_WX_INDEX_TOPIC);
    }


    public function getFreightValue(){
        return (double)$this->get(self::LITEMALL_EXPRESS_FREIGHT_VALUE);
    }

    public function getFreightMin(){
        return (double)$this->get(self::LITEMALL_EXPRESS_FREIGHT_MIN);
    }

    public function get($key){
        System::query()->where('key_name')->first(['key_value']);
        $value=['key_value']??null;
        if($value=='false'||$value=='FALSE'){
            return false;
        }
        if ($value=='true'||$value=='TRUE'){
            return true;
        }
        return  $value;
    }

    public function getOrderUnpaidDelayMinutes(){
        return (int) $this->get(self::LITEMALL_ORDER_UNPAID);

    }

    // 订单收货超时时间
    public function getOrderUnConfirmDays()
    {
        return (int) $this->get(self::LITEMALL_ORDER_UNCONFIRM);
    }

    public function getBrandLimit()
    {
        return $this->get(self::LITEMALL_WX_INDEX_BRAND);
    }

    public function getNewLimit()
    {
        return $this->get(self::LITEMALL_WX_INDEX_NEW);
    }

    public function getHotLimit()
    {
        return $this->get(self::LITEMALL_WX_INDEX_HOT);
    }

}


