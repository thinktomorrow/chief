<?php

namespace Thinktomorrow\Chief\Settings;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

class Settings extends Collection
{
    public static function configValues(): array
    {
        return config('thinktomorrow.chief-settings');
    }

    public function get($key, $locale = null, $default = null)
    {
        $this->fetch();

        if (! isset($this->items[$key])) {
            return $default;
        }
        
        if (is_array($this->items[$key])) {
            if ($this->items[$key]['value'] == null) {
                return $default;
            }

            return $this->items[$key]['value'];
        }

        return $this->items[$key];
    }

    public function set($key, $value)
    {
        $this->items[$key] = $value;
    }

    private function fetch()
    {
        if ($this->items) {
            return;
        }

        $config_values = static::configValues();

        $database_values = Schema::hasTable((new Setting)->getTable())
            ? Setting::all()->pluck('value', 'key')->toArray()
            : [];

        $this->items = array_merge($config_values, $database_values);
    }

    public function fresh()
    {
        $this->items = null;

        return $this;
    }
}
