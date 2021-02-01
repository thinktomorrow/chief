<?php


namespace Thinktomorrow\Chief\Tests\Shared;

use Illuminate\Support\Arr;

trait PageFormParams
{
    protected function validPageParams($overrides = [])
    {
        $params = [
            'trans' => [
                'nl' => [
                    'title_trans' => 'new title',
                    'content_trans' => 'xx',
                ],
                'en' => [
                    'title_trans' => 'nouveau title',
                    'content_trans' => 'xx',
                ],
            ],
            'url-slugs' => [
                'nl' => 'new-slug',
                'en' => 'nouveau-slug',
            ],
            'custom' => 'xxx',
        ];

        foreach ($overrides as $key => $value) {
            Arr::set($params, $key, $value);
        }

        return $params;
    }

    protected function validUpdatePageParams($overrides = [])
    {
        $params = [
            'trans' => [
                'nl' => [
                    'title' => 'aangepaste title',
                    'seo_title' => 'aangepaste seo title',
                    'seo_description' => 'aangepaste seo description',
                    'seo_keywords' => 'aangepaste seo keywords',
                ],
                'en' => [
                    'title' => 'updated title',
                    'seo_title' => 'updated seo title',
                    'seo_description' => 'updated seo description',
                    'seo_keywords' => 'updated seo keywords',
                ],
            ],
            'url-slugs' => [
                'nl' => 'aangepaste-slug',
                'en' => 'updated-slug',
            ],
            'relations' => [],
        ];

        foreach ($overrides as $key => $value) {
            Arr::set($params, $key, $value);
        }

        return $params;
    }

    protected function assertNewPageValues($page)
    {
        $this->assertEquals('new title', $page->{'title:nl'});
        $this->assertNull($page->{'seo_title:nl'});
        $this->assertNull($page->{'seo_description:nl'});
        $this->assertNull($page->{'seo_keywords:nl'});

        $this->assertEquals('nouveau title', $page->{'title:en'});
        $this->assertNull($page->{'seo_title:en'});
        $this->assertNull($page->{'seo_description:en'});
        $this->assertNull($page->{'seo_keywords:en'});

        $this->assertStringEndsWith('new-slug', $page->url('nl'));
        $this->assertStringEndsWith('nouveau-slug', $page->url('en'));
    }

    protected function assertUpdatedPageValues($page)
    {
        $this->assertEquals('aangepaste title', $page->{'title:nl'});
        $this->assertEquals('aangepaste seo title', $page->{'seo_title:nl'});
        $this->assertEquals('aangepaste seo description', $page->{'seo_description:nl'});
        $this->assertEquals('aangepaste seo keywords', $page->{'seo_keywords:nl'});

        $this->assertEquals('updated title', $page->{'title:en'});
        $this->assertEquals('updated seo title', $page->{'seo_title:en'});
        $this->assertEquals('updated seo description', $page->{'seo_description:en'});
        $this->assertEquals('updated seo keywords', $page->{'seo_keywords:en'});

        $this->assertStringEndsWith('aangepaste-slug', $page->url('nl'));
        $this->assertStringEndsWith('updated-slug', $page->url('en'));
    }
}
