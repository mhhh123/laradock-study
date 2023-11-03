<?php

namespace App\Inputs;

use App\CodeResponse;
use App\Exceptions\BusinessException;
use App\VerifyRequestInput;
use Illuminate\Support\Facades\Validator;

class Input{
    use VerifyRequestInput;

    /**
     * @param null|array $data
     * @return Input
     * @throws BusinessException
     */
    public function fill($data=null){
        if(is_null($data)){
            $data=request()->input();
        }
        $validator=Validator::make($data,$this->rules());
        if ($validator->fails()){
            throw new  BusinessException(CodeResponse::PARAM_ILLEGAL);
        }
        $map=get_object_vars($this);
        $keys=array_keys($map);
        collect($data)->map(function ($v,$k)use ($keys){
            if (in_array($k,$keys)){
                $this->$k=$v;
            }

        });
        return $this;
    }
    public function rules(){
        return [];
    }

    /**
     * @param string $scene
     * @param null|array $data
     * @return Input|static
     * @throws BusinessException
     */
    public static function new(string $scene, $data=null){
        return (new static())->fill($scene,$data);
    }
}
