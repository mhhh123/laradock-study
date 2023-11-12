<?php
namespace App;

use App\Exceptions\BusinessException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

trait VerifyRequestInput{

    public function verifyArrayNotEmpty($key,$default=null,$enum = []){
        return $this->verifyData($key,$default,'array|min:1');
    }
    /**
     * @param $key
     * @param $default
     * @return mixed
     * @throws BusinessException
     */

    public function verifyId($key, $default=null){
        return $this->verifyData($key,$default,'integer|digits_between:1,20');
    }

    /**
     * @param $key
     * @param $default
     * @return mixed
     * @throws BusinessException
     */
    public function verifyString($key,$default=null){
        return $this->verifyData($key,$default,'string');
    }

    /**
     * @param $key
     * @param $default
     * @return mixed
     * @throws BusinessException
     */
    public function verifyBoolean($key, $default=null){
        return $this->verifyData($key, $default,'boolean');
    }

    /**
     * @param $key
     * @param $default
     * @return mixed
     * @throws BusinessException
     */

    public function verifyInteger($key, $default=null){
        return $this->verifyData($key, $default,'integer');
    }

    /**
     * @param $key
     * @param $default
     * @return mixed
     * @throws BusinessException
     */
    public function verifyPositiveInteger($key, $default=null){
        return $this->verifyData($key, $default,'integer|min:1');
    }


    /**
     * @param $key
     * @param $default
     * @param $enum
     * @return mixed
     * @throws BusinessException
     */

    public function verifyEnum($key, $default=null, $enum=[]){
        return $this->verifyData($key, $default, Rule::in($enum));
    }

    /**
     * @param $key
     * @param $default
     * @param $rule
     * @return mixed
     * @throws BusinessException
     */

    public function verifyData($key, $default,$rule){
        $value=request()->input($key, $default);
        $validator=Validator::make([
            $key=>$value
        ],[
            $key=>$rule
        ]);
        if ($validator->fails()){
            throw new BusinessException(CodeResponse::PARAM_VALUE_ILLEGAL);
        }
        return $value;

    }
}
