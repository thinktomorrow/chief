<x-chief::field.form
    label="{{ $label ?? '' }}"
    name="{{ $name }}"
>
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
</x-chief::field.form>
