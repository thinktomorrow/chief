@include('chief-forms::fields.text', [
    'passedAttributes' => [
        'type' => 'date',
        'min' => $getMin() ?? null,
        'max' => $getMax() ?? null,
        'step' => $getStep() ?? null,
    ],
])
