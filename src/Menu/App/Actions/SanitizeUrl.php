<?php

namespace Thinktomorrow\Chief\Menu\App\Actions;

use Illuminate\Support\Str;
use Thinktomorrow\Url\Root;
use Thinktomorrow\Url\Url;

class SanitizeUrl
{
    public function sanitize(string $url): string
    {
        if ($this->isRelativeUrl($url)) {
            return '/'.trim($url, '/');
        }

        if (Str::startsWith($url, ['mailto:', 'tel:', 'https://', 'http://'])) {
            return $url;
        }

        return Url::fromString($url)->secure()->get();
    }

    private function isRelativeUrl($url): bool
    {
        $nakedUrl = ltrim($url, '/');

        // Check if passed url is not intended as a host instead of a relative path
        $notIntentedAsRoot = (Root::fromString($url)->getScheme() == null && strpos($url, '.') === false);

        // Account for accidental double slash for relative paths
        return $notIntentedAsRoot && in_array($url, [$nakedUrl, '/'.$nakedUrl, '//'.$nakedUrl]);
    }
}
