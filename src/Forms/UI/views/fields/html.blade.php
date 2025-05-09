@php
    use Thinktomorrow\Chief\Forms\Fields\FieldName\LivewireFieldName;
@endphp

{{--
    Also needs to be wire:model.live to make Livewire modeling work with the redactor input event
--}}

<div wire:ignore>
    <x-chief::form.input.textarea
        data-editor
        data-locale="{{ $locale ?? app()->getLocale() }}"
        data-custom-redactor-options="{{ json_encode($getRedactorOptions($locale ?? null)) }}"
        wire:model.live="{{ LivewireFieldName::get($getName($locale ?? null)) }}"
        v-pre
        id="{{ $getId($locale ?? null) }}"
        name="{{ $getName($locale ?? null) }}"
        cols="10"
        rows="5"
        :autofocus="$hasAutofocus()"
        :attributes="$attributes->merge($getCustomAttributes())"
        style="resize: vertical"
    >{{ $getActiveValue($locale ?? null) }}</x-chief::form.input.textarea>
</div>


