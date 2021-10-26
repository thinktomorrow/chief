<x-chief::field
        label="{{ $label ?? '' }}"
        name="{{ $name }}"
>
    @if(isset($description))
        <x-slot name="description">
            <p>{!! $description !!}</p>
        </x-slot>
    @endif

    <input type="text" name="{{ $name }}" id="{{ $id }}" class="input inset-s" placeholder="{{ $placeholder }}" value="{{ $value }}">
</x-chief::field>
