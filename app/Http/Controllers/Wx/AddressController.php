<?php
namespace App\Http\Controllers\Wx;

use App\CodeResponse;

use App\Models\User\Address;
use App\Services\User\AddressServices;
use Illuminate\Http\Request;

class AddressController extends WxController
{
    /**获取用户地址
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(){

        $list=AddressServices::getInstance()->getAddressListByUserId($this->user()->id);
        return $this->successpaginate($list);
//        $list->map(function (Address $address){
//                $address=$address->toArray();
//                $item=[];
//                foreach ($address as $key=>$value){
//                    $key=lcfirst(\Illuminate\Support\Str::studly($key));
//                    $item[$key]=$value;
//                }
//                    return $item;
//        });
//        return $this->success([
//            'total'=>$list->count(),
//            'page'=>1,
//            'list'=>$list->toArray(),
//            'pages'=>1
//
//        ]);
    }

    /**收货地址详情
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function detail(Request $request){
        $id = $request->input('id', 0);
        $address = AddressServices::getInstance()->getAddress($this->user()->id, $id);
        if (is_null($address)) {
            $this->fail(CodeResponse::PARAM_ILLEGAL);
        }
        return $this->success($address);
    }

   // public function save(){}

    /**地址删除
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\BusinessException
     */
    public function delete(Request $request){
        $id=$request->input('id',0);
        if(empty($id)&&!is_numeric($id)){
            return $this->fail(CodeResponse::PARAM_ILLEGAL);
        }
        AddressServices::getInstance()->delete($this->user()->id,$id);
        return $this->success();
    }
}
