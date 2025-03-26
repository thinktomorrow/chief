@props([
    'rule' => null,
])

@if ($rule)
    @if ($errors->any())
        @error($rule)
            <x-chief::callout data-slot="error" size="sm" variant="red" class="px-2.5 py-1">
                {{ ucfirst($message) }}
            </x-chief::callout>
        @enderror
    @endif

    <x-chief::callout
        data-slot="error"
        data-error-placeholder="{{ $rule }}"
        size="sm"
        variant="red"
        class="hidden px-2.5 py-1"
    >
        <div data-error-placeholder-content></div>
    </x-chief::callout>
@endif
