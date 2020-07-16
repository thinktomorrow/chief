<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\System\HealthMonitor\Checks;

use PackageVersions\Versions;
use Thinktomorrow\Chief\System\HealthMonitor\Notifiers\AlertBarNotifier;

class ChiefVersionCheck implements HealthCheck
{
    public function check(): bool
    {
        $options = [
            'http'=>[
              'method'=>"GET",
              'header'=> [
                  "User-Agent: PHP"
              ]
            ]
        ];

        $context = stream_context_create($options);
        $content = file_get_contents("https://api.github.com/repos/thinktomorrow/chief/releases/latest", false, $context);
        $latest_tag = json_decode($content)->tag_name;
        $current_tag = explode('@', Versions::getVersion('thinktomorrow/chief'))[0];

        return $latest_tag == $current_tag;
    }

    public function message(): string
    {
        return 'Er is een nieuwe versie van chief beschikbaar. Contacteer Think Tomorrow om te weten wat dit inhoud en of dit voor u een verbetering kan zijn.';
    }

    public function notifiers(): array
    {
        return [
            AlertBarNotifier::class,
        ];
    }
}
