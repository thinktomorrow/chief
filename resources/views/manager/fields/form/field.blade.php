<x-chief-formgroup
    label="{{ $field->getLabel() }}"
    name="{{ $field->getName($locale ?? null) }}"
    id="{{ $field->getId($locale ?? null) }}"
    isRequired="{{ $field->required() }}"
    data-conditional="{{ $field->getId($locale ?? null) }}"
    data-conditional-trigger-type="{{ $field->getType() }}"
    data-conditional-data="{{ $field->getConditionalFieldsData() }}"
    class="{{ $field->getWidthStyle() }}"
>
    @if($field->getDescription())
        <x-slot name="description">
            <p>{!! $field->getDescription() !!}</p>
        </x-slot>
    @endif

    @isset($slot)
        {{ $slot }}
    @else
        {!! $field->render(get_defined_vars()) !!}
    @endisset
</x-chief-formgroup>