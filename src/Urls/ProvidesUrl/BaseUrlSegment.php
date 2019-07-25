<?php

namespace Thinktomorrow\Chief\Urls\ProvidesUrl;

use Thinktomorrow\Chief\Management\Managers;

class BaseUrlSegment
{
    public static function strip($value)
    {
        $originalValue = $value = trim($value, '/');

        $segments = static::all();

        foreach ($segments as $segment) {
            if (0 === strpos($originalValue, $segment)) {
                $value = substr($value, strlen($segment));
            }
        }

        return trim($value, '/');
    }

    private static function all(): array
    {
        $segments = [];

        $managers = app(Managers::class)->all();

        foreach ($managers as $manager) {
            if (contract($manager->model(), ProvidesUrl::class)) {
                $segments[] = $manager->model()->baseUrlSegment();
            }
        }

        return array_unique($segments);
    }
}
