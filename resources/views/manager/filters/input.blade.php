<x-chief-form::formgroup id="{{ $id }}" label="{{ $label ?? null }}">
    @if(isset($description))
        <x-slot name="description">
            <p>{!! $description !!}</p>
        </x-slot>
    @endif

    <input
        id="{{ $id }}"
        type="text"
        name="{{ $name }}"
        placeholder="{{ $placeholder }}"
        value="{{ $value }}"
    >
</x-chief-form::formgroup>
