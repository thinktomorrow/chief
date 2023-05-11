<x-chief::input.textarea
    data-editor
    data-locale="{{ $locale ?? app()->getLocale() }}"
    data-custom-redactor-options="{{ json_encode($getRedactorOptions($locale ?? null)) }}"
    wire:model="{{ \Thinktomorrow\Chief\Forms\Livewire\LivewireAssist::formDataIdentifier($getName(),$locale ?? null) }}"
    v-pre
    id="{{ $getId($locale ?? null) }}"
    name="{{ $getName($locale ?? null) }}"
    cols="10"
    rows="5"
    :autofocus="$hasAutofocus()"
    :attributes="$attributes->merge($getCustomAttributes())"
    style="resize: vertical"
>{{ $getActiveValue($locale ?? null) }}</x-chief::input.textarea>
