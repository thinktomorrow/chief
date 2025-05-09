<?php

namespace Thinktomorrow\Chief\Tests\Unit\Shared;

use PHPUnit\Framework\Attributes\DataProvider;
use Thinktomorrow\Chief\Forms\Tests\FormsTestCase;

class AddQueryToUrlFormsTest extends FormsTestCase
{
    public static function queryProvider()
    {
        return [
            ['http://example.com', ['foo' => 'bar'], 'http://example.com?foo=bar'],
            ['http://example.com', ['foo' => 'bar', 'bal' => 'boy'], 'http://example.com?foo=bar&bal=boy'],
            ['example.com', ['foo' => 'bar'], 'example.com?foo=bar'],
            ['http://example.com?baz=foz', ['foo' => 'bar'], 'http://example.com?baz=foz&foo=bar'],
            ['http://example.com?foo=bazz', ['foo' => 'bar'], 'http://example.com?foo=bar'],
            ['http://example.com?foo=', ['foo' => 'bar'], 'http://example.com?foo=bar'],
            ['http://example.com?baz=', ['foo' => 'bar'], 'http://example.com?baz=&foo=bar'],
            ['http://example.com#id', ['foo' => 'bar'], 'http://example.com?foo=bar#id'],
            ['http://example.com:3000?baz=foz', ['foo' => 'bar'], 'http://example.com:3000?baz=foz&foo=bar'],
            ['https://example.com:3000?baz=foz&bal=bozz#id', ['foo' => 'bar'], 'https://example.com:3000?baz=foz&bal=bozz&foo=bar#id'],
            ['http://optiphar.dev:8000/nl/online-apotheek/huidverzorging?pp=32&subcat=serum,anti-rimpel', ['pp' => 16], 'http://optiphar.dev:8000/nl/online-apotheek/huidverzorging?subcat=serum,anti-rimpel&pp=16'],
            ['http://example.com', ['foo' => 'bar'], 'http://fuzzz.com?foo=bar', ['host' => 'fuzzz.com']],
        ];
    }

    #[DataProvider('queryProvider')]
    public function test_add_query_to_url_adds_query_to_url($url, $query_params, $expected, $overrides = [])
    {
        $this->assertEquals($expected, addQueryToUrl($url, $query_params, $overrides));
    }
}
