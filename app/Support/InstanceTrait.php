<?php

namespace App\Support;

trait InstanceTrait
{
    protected static $_instances = [];

    protected $instanceKey;

    public static function getInstance()
    {
        $key = get_class();

        if (isset(static::$_instances[$key]) && static::$_instances[$key] instanceof static) {
            return static::$_instances[$key];
        }

        $client = new static();
        return static::$_instances[$key] = $client;
    }

    /**
     * @desc   回收单例对象
     * @author limx
     */
    public function flushInstance()
    {
        unset(static::$_instances[$this->instanceKey]);
    }
}
