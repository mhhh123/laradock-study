<?php
namespace App\Services;

use App\Exceptions\BusinessException;

class BaseServices
{
    protected static $instance;

    /**
     * @return static
     */
    public static function getInstance()
    {
        if (static::$instance instanceof static) {
            return static::$instance;
        }
        static::$instance = new static();
        return static::$instance;
    }

    public function __construct()
    {

    }

    private function __clone(): void
    {

    }

    /**
     * @param array $codeResponse
     * @param string $info
     * @return mixed
     * @throws BusinessException
     *
     */
    public function throwBusinessException(array $codeResponse,$info=''){
        throw new  BusinessException($codeResponse,$info);
    }
}

