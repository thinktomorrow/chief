<?php

declare(strict_types = 1);

namespace Thinktomorrow\Chief\HealthMonitor\Checks;

use Thinktomorrow\Chief\Settings\Setting;

class HomepageCheck implements HealthCheck
{
    public static function check()
    {
        $homepageValue = chiefSetting(Setting::HOMEPAGE);

        return !!$homepageValue;
    }

    public static function notify()
    {
        return 'Het lijkt erop dat er geen homepagina ingesteld is. Stel er een in hier: <a href="'. route('chief.back.settings.edit') .'">Settings</a>';
    }
}
