<?php

declare(strict_types = 1);

namespace Thinktomorrow\Chief\HealthMonitor\Checks;

use Thinktomorrow\Chief\Settings\Setting;

class HomepageCheck implements HealthCheck
{
    public function check(): bool
    {
        $homepageValue = chiefSetting(Setting::HOMEPAGE);

        return !!$homepageValue;
    }

    public function message(): string
    {
        return 'Het lijkt erop dat er geen homepagina ingesteld is. Stel er een in hier: <a href="'. route('chief.back.settings.edit') .'" class="text-secondary-800 underline hover:text-white">Settings</a>';
    }
}
