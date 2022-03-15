@include('chief-form::fields.text', [
    'passedAttributes' => [
        'type' => 'range',
        'min' => $getMin() ?? null,
        'max' => $getMax() ?? null,
        'step' => $getStep() ?? null,
    ],
])
