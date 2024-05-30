@php use Thinktomorrow\Chief\Forms\Livewire\LivewireFieldName; @endphp

<x-chief::input.textarea
    wire:model="{{ LivewireFieldName::get($getName($locale ?? null)) }}"
    id="{{ $getId($locale ?? null) }}"
    name="{{ $getName($locale ?? null) }}"
    :autofocus="$hasAutofocus()"
    :attributes="$attributes->merge($getCustomAttributes())"
    v-pre
    cols="10"
    rows="5"
    style="resize: vertical">{{ $getActiveValue($locale ?? null) }}</x-chief::input.textarea>
