<?php

namespace Thinktomorrow\Chief\Site\Urls;

use Thinktomorrow\Chief\ManagedModels\Presets\Page;

class CurrentContext
{
    private static array $map = [];

    public static function setUrlRecord(string $url): void
    {
        self::set('url', $url);
    }

    public static function getUrlRecord(): UrlRecord
    {
        return self::get('url');
    }

    public static function setPage(string $page): void
    {
        self::set('page', $page);
    }

    public static function getPage(): Page
    {
        return self::get('page');
    }

    private static function set(string $key, $value): void
    {
        self::$map[$key] = $value;
    }

    private static function get(string $key, $default = null)
    {
        return self::$map[$key] ?? $default;
    }
}
