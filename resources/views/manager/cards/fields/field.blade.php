<x-chief-formgroup
    label="{{ $field->getLabel() }}"
    name="{{ $field->getName($locale ?? null) }}"
    isRequired="{{ $field->required() }}"
    data-formgroup="{{ $field->getId($locale ?? null) }}"
    data-trigger-formgroup="{{ $field->getFormgroupsToTrigger() }}"
    data-trigger-formgroup-with-value="{{ $field->getValueToTriggerFormgroupsWith() }}"
>
    @if($field->getDescription())
        <x-slot name="description">
            <p>{!! $field->getDescription() !!}</p>
        </x-slot>
    @endif

    {!! $field->render(get_defined_vars()) !!}
</x-chief-formgroup>
