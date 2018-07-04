<?php

namespace Thinktomorrow\Chief\Settings;

class SettingsManager
{
    private $values;

    public function __construct()
    {
        //
    }

    public function get($key, $default = null)
    {
        $this->fetch();

        if( ! isset($this->values[$key])) return $default;

        return $this->values[$key];
    }

    public function set($key, $value)
    {
        $this->values[$key] = $value;
    }

    private function fetch()
    {
        if($this->values) return;

        $this->values = Setting::all()->pluck('value','key')->toArray();
    }

    public function fresh()
    {
        $this->values = null;

        return $this;
    }
}