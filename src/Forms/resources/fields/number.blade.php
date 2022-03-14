@include('chief-forms::fields.text', [
    'passedAttributes' => [
        'type' => 'number',
        'min' => $getMin() ?? null,
        'max' => $getMax() ?? null,
    ],
])
