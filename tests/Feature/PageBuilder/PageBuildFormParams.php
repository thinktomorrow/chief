<?php


namespace Thinktomorrow\Chief\Tests\Feature\PageBuilder;

use Illuminate\Support\Arr;
use Thinktomorrow\Chief\Management\Assistants\UrlAssistant;

trait PageBuildFormParams
{
    protected function validPageParams($overrides = [])
    {
        $params = [
            'sections' => [
                'modules' => [
                   //
                ],
                'text' => [
                    'new' => [
                        //
                    ],
                    'replace' => [
                       //
                    ],
                ],
                'order' => [
                    //
                ]
            ],
            'trans' => [
               'nl' => [
                   'title'=> 'title',
               ]
            ],
            'url-slugs' => [
                'nl' => 'slug',
            ],
        ];

        foreach ($overrides as $key => $value) {
            Arr::set($params, $key, $value);
        }

        return $params;
    }
}
