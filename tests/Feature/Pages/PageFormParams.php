<?php


namespace Thinktomorrow\Chief\Tests\Feature\Pages;

trait PageFormParams
{
    protected function validPageParams($overrides = [])
    {
        $params = [
            'trans' => [
                'nl' => [
                    'title' => 'new title',
                    'slug' => 'new-slug',
                ],
                'en' => [
                    'title' => 'nouveau title',
                    'slug' => 'nouveau-slug',
                ],
            ],
        ];

        foreach ($overrides as $key => $value) {
            array_set($params, $key, $value);
        }

        return $params;
    }

    protected function validUpdatePageParams($overrides = [])
    {
        $params = [
            'trans' => [
                'nl' => [
                    'slug' => 'aangepaste-slug',
                    'title' => 'aangepaste title',
                    'seo_title' => 'aangepaste seo title',
                    'seo_description' => 'aangepaste seo description',
                    'seo_keywords' => 'aangepaste seo keywords',
                ],
                'en' => [
                    'slug' => 'updated-slug',
                    'title' => 'updated title',
                    'seo_title' => 'updated seo title',
                    'seo_description' => 'updated seo description',
                    'seo_keywords' => 'updated seo keywords',
                ],
            ],
            'relations' => [],
        ];

        foreach ($overrides as $key => $value) {
            array_set($params, $key, $value);
        }

        return $params;
    }

    protected function assertNewPageValues($page)
    {
        $this->assertEquals('new-slug', $page->{'slug:nl'});
        $this->assertEquals('new title', $page->{'title:nl'});

        $this->assertNull($page->{'seo_title:nl'});
        $this->assertNull($page->{'seo_description:nl'});
        $this->assertNull($page->{'seo_keywords:nl'});

        $this->assertEquals('nouveau-slug', $page->{'slug:en'});
        $this->assertEquals('nouveau title', $page->{'title:en'});
        $this->assertNull($page->{'seo_title:en'});
        $this->assertNull($page->{'seo_description:en'});
        $this->assertNull($page->{'seo_keywords:en'});
    }

    protected function assertUpdatedPageValues($page)
    {
        $this->assertEquals('aangepaste-slug', $page->{'slug:nl'});
        $this->assertEquals('aangepaste title', $page->{'title:nl'});
        $this->assertEquals('aangepaste seo title', $page->{'seo_title:nl'});
        $this->assertEquals('aangepaste seo description', $page->{'seo_description:nl'});
        $this->assertEquals('aangepaste seo keywords', $page->{'seo_keywords:nl'});

        $this->assertEquals('updated-slug', $page->{'slug:en'});
        $this->assertEquals('updated title', $page->{'title:en'});
        $this->assertEquals('updated seo title', $page->{'seo_title:en'});
        $this->assertEquals('updated seo description', $page->{'seo_description:en'});
        $this->assertEquals('updated seo keywords', $page->{'seo_keywords:en'});
    }
}
