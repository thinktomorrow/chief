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
                        //
                    ],
                    'remove' => [
                        //
                    ],
                ],
                'text' => [
                    'new' => [
                        //
                    ],
                    'replace' => [
                       //
                    ],
                    'remove' => [
                        //
                    ],
                ],
                'order' => [
                    //
                ]
            ],
        ];

        foreach ($overrides as $key => $value) {
            array_set($params, $key, $value);
        }

        return $params;
    }
}
