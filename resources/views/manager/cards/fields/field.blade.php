<x-chief-formgroup
        label="{{ $field->getLabel() }}"
        name="{{ $field->getName($locale ?? null) }}"
        isRequired="{{ $field->required() }}"
        data-toggle-field-target="{{ $field->getId($locale ?? null) }}"
>
    @if($field->getDescription())
        <x-slot name="description">
            <p>{{ $field->getDescription() }}</p>
        </x-slot>
    @endif

    {!! $field->render(get_defined_vars()) !!}
</x-chief-formgroup>
