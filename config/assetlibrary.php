<?php

return [
    'models' => [
        'locale' => Thinktomorrow\Locale\Locale::class,

    ],
    'conversionPrefix'  => false,
    'conversions' => [
        'thumb' => [
            'width'     => 150,
            'height'    => 150,
        ],
        'medium' => [
            'width'     => 300,
            'height'    => 130,
        ],
        'large' => [
            'width'     => 1024,
            'height'    => 353,
        ],
        'full' => [
            'width'     => 1600,
            'height'    => 553,
        ],
    ],

    'allowCropping' => false
];
