<?php

namespace App\Console\Chief;

class ChiefConfig
{
    public function __construct()
    {
        //
    }

    protected static $attributes = [];

    public function get($key)
    {
        if(isset(self::$attributes[$key])) return self::$attributes[$key];

        return null;
    }

    public function set($key,$value)
    {
        self::$attributes[$key] = $value;

        return $this;
    }

    public static function __callStatic($method,$arg)
    {
        return (new self)->get($method);
    }
}