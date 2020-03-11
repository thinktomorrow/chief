<?php

declare(strict_types=1);

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

        if (!isset($this->items[$key])) {
            return $default;
        }

        if (is_array($this->items[$key])) {
            if (!$locale) {
                $locale = app()->getLocale();
            }

            if ($this->items[$key] == null || !isset($this->items[$key][$locale])) {
                return $default;
            }

            return $this->items[$key][$locale] ?? $default;
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

        $database_values = Schema::hasTable((new Setting())->getTable())
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
