<?php
namespace App\Models\Order;

use App\CodeResponse;
use App\Constant;
use App\Exceptions\BusinessException;
use App\Models\BaseModel;
use App\Services\Order\OrderServices;

trait OrderStatusTrait{

    public function  canCancelHandle(){
        return $this->order_status!=Constant::STATUS_CREATE;
    }

    public function canPayHandle(){
        return $this->order_status==Constant::STATUS_CREATE;
    }

    public function canrefundHandle(){
        return $this->order_status==Constant::STATUS_PAY;
    }

    public function canAgreeRefundHandle(){
        return $this->order_status==Constant::STATUS_REFUND;
    }

    public function canConfirmHandle(){
        return $this->order_status==Constant::STATUS_SHIP;
    }

    public function canDeletedHandle(){
        return in_array($this->order_status,[
            Constant::STATUS_CANCEL,
            Constant::STATUS_AUTO_CANCEL,
            Constant::STATUS_ADMIN_CANCEL,
            Constant::STATUS_REFUND_CONFIRM,
            Constant::STATUS_CONFIRM,
            Constant::STATUS_AUTO_CONFIRM
        ]);
    }

    public function canCommentHandle(){
        return in_array($this->order_status,[
            Constant::STATUS_CONFIRM,
            Constant::STATUS_AUTO_CONFIRM
            ]);
    }

    public function canRebuyHandle(){
        return in_array($this->order_status,[
            Constant::STATUS_CONFIRM,
            Constant::STATUS_AUTO_CONFIRM
        ]);
    }

    public function canAfterSaleHandle(){
        return in_array($this->order_status,[
            Constant::STATUS_CONFIRM,
            Constant::STATUS_AUTO_CONFIRM
        ]);
    }

    public function getCanHandleOptions(){
        return [
            'cancel'=>$this->canCancelHandle(),
            'deleted'=>$this->canDeletedHandle(),
            'pay'=>$this->canPayHandle(),
            'comment'=>$this->canCommentHandle(),
            'confirm'=>$this->canConfirmHandle(),
            'refund'=>$this->canrefundHandle(),
            'rebuy'=>$this->canRebuyHandle(),
            'aftersale'=>$this->canAfterSaleHandle()
        ];
    }

    public function isShipStatus(){
        return $this->order_status==Constant::STATUS_SHIP;
    }

    public function isPayStatus(){
        return $this->order_status==Constant::STATUS_PAY;
    }

    public function isHadPaid(){
        return !in_array($this->order_status,[
            Constant::STATUS_CREATE,
            Constant::STATUS_ADMIN_CANCEL,
            Constant::STATUS_AUTO_CANCEL,
            Constant::STATUS_CANCEL
        ]);
    }




    public function handle(){
        try {
            OrderServices::getInstance()->systemCancel($this->userId,$this->orderId);
        }catch(BusinessException $exception){
        }

    }
}
