<?php


namespace Thinktomorrow\Chief\Tests\Feature\PageBuilder;

trait PageBuildFormParams
{
    protected function validPageParams($overrides = [])
    {
        $params = [
            'sections' => [
                'modules' => [
                    'new' => [

                    ],
                    'remove' => [

                    ],
                ],
                'text' => [
                    'new' => [
                        [
                            'id' => null,
                            'slug' => 'text-1',
                            'trans' => [
                                'nl' => [
                                    'content' => 'new content',
                                ]
                            ]
                        ],
                    ],
                    'replace' => [
//                        [
//                            'id' => 2,
//                            'trans' => [
//                                'nl' => [
//                                    'content' => 'replaced content',
//                                ]
//                            ]
//                        ]
                    ],
                    'remove' => [
//                        [
//                            2,
//                        ]
                    ],
                ],
                'order' => [
                    2, 4, 5, 'text-1', 5
                ]
            ],
        ];

        foreach ($overrides as $key => $value) {
            array_set($params, $key, $value);
        }

        return $params;
    }

    protected function assertUpdatedPageValues($page)
    {
        // RUN OVER SECTIONS


        $this->assertEquals('aangepaste-slug', $page->{'slug:nl'});
        $this->assertEquals('aangepaste title', $page->{'title:nl'});
        $this->assertEquals('aangepaste seo title', $page->{'seo_title:nl'});
        $this->assertEquals('aangepaste seo description', $page->{'seo_description:nl'});

        $this->assertEquals('updated-slug', $page->{'slug:en'});
        $this->assertEquals('updated title', $page->{'title:en'});
        $this->assertEquals('updated seo title', $page->{'seo_title:en'});
        $this->assertEquals('updated seo description', $page->{'seo_description:en'});
    }
}
