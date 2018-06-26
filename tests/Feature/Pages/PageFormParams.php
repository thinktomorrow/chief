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
                    'content' => 'aangepaste content in <strong>bold</strong>',
                    'short' => 'aangepaste intro in <strong>bold</strong>',
                    'seo_title' => 'aangepaste seo title',
                    'seo_description' => 'aangepaste seo description',
                ],
                'en' => [
                    'slug' => 'updated-slug',
                    'title' => 'updated title',
                    'content' => 'updated content in <strong>bold</strong>',
                    'short' => 'updated intro in <strong>bold</strong>',
                    'seo_title' => 'updated seo title',
                    'seo_description' => 'updated seo description',
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

        $this->assertNull($page->{'short:nl'});
        $this->assertNull($page->{'content:nl'});
        $this->assertNull($page->{'seo_title:nl'});
        $this->assertNull($page->{'seo_description:nl'});

        $this->assertEquals('nouveau-slug', $page->{'slug:en'});
        $this->assertEquals('nouveau title', $page->{'title:en'});
        $this->assertNull($page->{'short:en'});
        $this->assertNull($page->{'content:en'});
        $this->assertNull($page->{'seo_title:en'});
        $this->assertNull($page->{'seo_description:en'});
    }

    protected function assertUpdatedPageValues($page)
    {
        $this->assertEquals('aangepaste-slug', $page->{'slug:nl'});
        $this->assertEquals('aangepaste title', $page->{'title:nl'});
        $this->assertEquals('aangepaste intro in <strong>bold</strong>', $page->{'short:nl'});
        $this->assertEquals('aangepaste content in <strong>bold</strong>', $page->{'content:nl'});
        $this->assertEquals('aangepaste seo title', $page->{'seo_title:nl'});
        $this->assertEquals('aangepaste seo description', $page->{'seo_description:nl'});

        $this->assertEquals('updated-slug', $page->{'slug:en'});
        $this->assertEquals('updated title', $page->{'title:en'});
        $this->assertEquals('updated intro in <strong>bold</strong>', $page->{'short:en'});
        $this->assertEquals('updated content in <strong>bold</strong>', $page->{'content:en'});
        $this->assertEquals('updated seo title', $page->{'seo_title:en'});
        $this->assertEquals('updated seo description', $page->{'seo_description:en'});
    }
}
