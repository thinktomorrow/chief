@include('chief-form::fields.text', [
    'passedAttributes' => [
        'type' => 'number',
        'min' => $getMin() ?? null,
        'max' => $getMax() ?? null,
        'step' => $getStep() ?? null,
    ],
])
