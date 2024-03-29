@php use Thinktomorrow\Chief\Forms\Livewire\LivewireFieldName; @endphp
<x-chief::input.textarea
        wire:model="{{ LivewireFieldName::get($getName($locale ?? null)) }}"
        v-pre
        id="{{ $getElementId($locale ?? null) }}"
        name="{{ $getName($locale ?? null) }}"
        cols="5"
        rows="5"
        :autofocus="$hasAutofocus()"
        :attributes="$attributes->merge($getCustomAttributes())"
        style="resize: vertical"
>{{ $getActiveValue($locale ?? null) }}</x-chief::input.textarea>
