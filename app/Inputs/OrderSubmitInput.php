<?php
namespace App\Inputs;

use http\Message;

class OrderSubmitInput extends Input{
    public $cartId;
    public $addressId;
    public $couponId;
    public $userCouponId;
    public $message;
    public $grouponRulesId;
    public $grouponLinkId;

    public function rules()
    {
        return [
            'cartId'=>'integer',
            'address'=>'integer',
            'couponId'=>'integer',
            'userCouponId'=>'integer',
            'message'=>'string',
            'grouponRulesId'=>'integer',
            'grouponLinkId'=>'integer',
        ];
    }
}
