<?php


namespace Thinktomorrow\Chief\Tests\Feature\PageBuilder;

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
                   'title'=> 'slug',
               ]
            ]
        ];

        foreach ($overrides as $key => $value) {
            array_set($params, $key, $value);
        }

        return $params;
    }
}
