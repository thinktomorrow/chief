<?php

namespace Thinktomorrow\Chief\Settings\Console;

use Illuminate\Console\Command;
use Thinktomorrow\Chief\Settings\Setting;

class SeedSettings extends Command
{
    protected $signature   = 'chief:settings';
    protected $description = 'Seed the database with the settings from the config.';

    public function handle()
    {
        foreach (chiefSetting()->configValues() as $key => $value) {
            if (Setting::where('key', $key)->first()) {
                continue;
            }

            Setting::create([
                'key' => $key,
                'value' => $value,
            ]);
        }
    }
}
