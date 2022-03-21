@include('chief-form::fields.text', [
    'passedAttributes' => [
        'type' => 'date',
        'min' => $getMin() ?? null,
        'max' => $getMax() ?? null,
        'step' => $getStep() ?? null,
    ],
])
