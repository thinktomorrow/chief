<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Admin\HealthMonitor\Checks;

use Thinktomorrow\Chief\Admin\HealthMonitor\Notifiers\AlertBarNotifier;
use Thinktomorrow\Chief\Admin\Settings\Setting;

class HomepageSetCheck implements HealthCheck
{
    public function check(): bool
    {
        $homepageValue = chiefSetting(Setting::HOMEPAGE);

        return ! ! $homepageValue;
    }

    public function message(): string
    {
        return 'Het lijkt erop dat er geen homepagina ingesteld is. Stel er een in hier: <a href="' . route('chief.back.settings.edit') . '" class="link">Settings</a>';
    }

    /**
     * @return string[]
     *
     * @psalm-return array{0: string}
     */
    public function notifiers(): array
    {
        return [
            AlertBarNotifier::class,
        ];
    }
}
