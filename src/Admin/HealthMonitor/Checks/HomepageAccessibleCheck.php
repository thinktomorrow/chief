<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Admin\HealthMonitor\Checks;

use Thinktomorrow\Chief\Admin\HealthMonitor\Notifiers\AlertBarNotifier;
use Thinktomorrow\Chief\Admin\Settings\Homepage;

class HomepageAccessibleCheck implements HealthCheck
{
    public function check(): bool
    {
        return $this->get_http_response_code(Homepage::url()) == 200;
    }

    /**
     * @return false|string
     */
    private function get_http_response_code(string $url)
    {
        if ($url == '') {
            return false;
        }

        // Avoid ssl errors: SSL operation failed with code 1
        stream_context_set_default([
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ],
        ]);

        $headers = get_headers($url);

        return substr($headers[0], 9, 3);
    }

    public function message(): string
    {
        return 'Het lijkt erop dat de homepagina niet meer bereikbaar is. <a href="' . route('chief.back.settings.edit') . '" class="text-secondary-800 underline hover:text-white">Kies een nieuwe</a>.';
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
