<x-chief-form::formgroup id="{{ $id }}" label="{{ $label ?? null }}">
    @if(isset($description))
        <x-slot name="description">
            <p>{!! $description !!}</p>
        </x-slot>
    @endif

        <chief-multiselect
            id="{{ $id }}"
            name="{{ $name }}"
            :options='@json($options)'
            selected='@json($value)'
            :multiple='@json($multiple)'
        ></chief-multiselect>
</x-chief-form::formgroup>
