<?php

namespace Thinktomorrow\Chief\Settings\Console;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Thinktomorrow\Chief\App\Console\BaseCommand;
use Thinktomorrow\Chief\Common\Publish\Publishable;
use Thinktomorrow\Chief\Common\Traits\Sortable;
use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Settings\Setting;

class SeedSettings extends BaseCommand
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
