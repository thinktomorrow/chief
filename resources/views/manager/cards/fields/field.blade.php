<x-chief-formgroup
    label="{{ $field->getLabel() }}"
    name="{{ $field->getName($locale ?? null) }}"
    isRequired="{{ $field->required() }}"
    data-formgroup="{{ $field->getId($locale ?? null) }}"
    data-formgroup-type="{{ $field->getType() }}"
    data-conditional-fields-data="{{ $field->getConditionalFieldsData() }}"
>
    @if($field->getDescription())
        <x-slot name="description">
            <p>{!! $field->getDescription() !!}</p>
        </x-slot>
    @endif

    {!! $field->render(get_defined_vars()) !!}
</x-chief-formgroup>
