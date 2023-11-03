<?php
namespace App\Services\User;

use App\CodeResponse;
use App\Exceptions\BusinessException;

use App\Models\Order\Cart;
use App\Models\User\Address;
use App\Services\BaseServices;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class AddressServices extends BaseServices
{
    /**获取地址或返回默认地址
     * @param $userId
     * @param null $addressId
     * @return mixed|void
     * @throws BusinessException
     */
    public function getAddressOrDefault($userId,$addressId=null){
        if(empty($addressId)){
            AddressServices::getInstance()->getDefaultAddress($userId);
        }else{
            $address=AddressServices::getInstance()->getAddress($userId,$addressId);
            if (empty($address)){
                return $this->throwBusinessException(CodeResponse::PARAM_VALUE_ILLEGAL);
            }
        }
    }

    public function getDefaultAddress($userId){
        return Address::query()->where('user_id',$userId)
            ->where('is_default',1)->first();
    }

    /**
     * @param int $userId
     * @return Address|\Illuminate\Database\Eloquent\Builder|Model|object|null
     */
    public function getAddressListByUserId(int $userId)
    {
        return Address::query()
            ->where('user_id', $userId)
            ->get();
    }


    /**
     * @param $userId
     * @param $addressId
     * @return Address|Model|null
     */

    public function getAddress($userId, $addressId){
        return Address::query()
            ->where('user_id',$userId)
            ->where('id',$addressId)
            ->first();
    }

    /** 删除用户地址
     * @param $userId
     * @param $addressId
     * @return bool|null
     * @throws BusinessException
     */
    public function delete($userId, $addressId)
    {
        $address=$this->getAddress($userId,$addressId);
        if (is_null($address)){
            throw new BusinessException(CodeResponse::PARAM_ILLEGAL);
        }
        return $address->delete();
    }
}

