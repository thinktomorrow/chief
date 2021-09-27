<x-chief-formgroup
    label="{{ $field->getLabel() }}"
    :field="$field"
    name="{{ $field->getName() }}"
    isRequired="{{ $field->required() }}"
    data-conditional="{{ $field->getId() }}"
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
