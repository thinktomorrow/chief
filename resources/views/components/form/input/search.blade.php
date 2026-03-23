@props([
    'autofocus' => false,
])

<div data-slot="control" class="relative flex grow items-center">
    <input
        type="text"
        {{ $attributes->merge(['data-slot' => 'control'])->class('form-input-field peer py-1.5 ps-9') }}
    />

    <x-chief::icon.search class="text-grey-500 peer-focus:text-grey-800 absolute left-2.5 size-5" />
</div>
