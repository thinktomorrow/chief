@include('chief-forms::fields.text', [
    'passedAttributes' => [
        'type' => 'range',
        'min' => $getMin() ?? null,
        'max' => $getMax() ?? null,
        'step' => $getStep() ?? null,
    ],
])
