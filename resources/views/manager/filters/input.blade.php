<x-chief-forms::formgroup.wrapper id="{{ $id }}" label="{{ $label ?? null }}">
    @if(isset($description))
        <x-slot name="description">
            <p>{!! $description !!}</p>
        </x-slot>
    @endif

    <input id="{{ $id }}" type="text" name="{{ $name }}" placeholder="{{ $placeholder }}" value="{{ $value }}">
    <x-chief-forms::formgroup.error error-ids="{{ $id }}"></x-chief-forms::formgroup.error>
</x-chief-forms::formgroup.wrapper>
