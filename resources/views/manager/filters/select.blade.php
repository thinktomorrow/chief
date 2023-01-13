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
            selected='@json($value ?: $default)'
            :multiple='@json($multiple)'
            @if($isGrouped)
                grouplabel="group"
                groupvalues="values"
                labelkey="label"
                valuekey="id"
            @endif
        ></chief-multiselect>
</x-chief-form::formgroup>
