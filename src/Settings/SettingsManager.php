<?php

namespace Thinktomorrow\Chief\Settings;

use Illuminate\Support\Facades\Schema;

class SettingsManager
{
    private $values;

    public function get($key, $default = null)
    {
        $this->fetch();

        if( ! isset($this->values[$key] )) return $default;
        
        if( is_array($this->values[$key]) ) {
            
            if($this->values[$key]['value'] == null) return $default;

            return $this->values[$key]['value'];
        }

        return $this->values[$key];
    }

    public function set($key, $value)
    {
        $this->values[$key] = $value;
    }

    private function fetch()
    {
        if($this->values) return;

        $config_values   = config('thinktomorrow.chief-settings');
        
        $database_values = Schema::hasTable((new Setting)->getTable())
            ? Setting::all()->pluck('value','key')->toArray()
            : [];

        $this->values = array_merge($config_values, $database_values);
    }

    public function fresh()
    {
        $this->values = null;

        return $this;
    }
}