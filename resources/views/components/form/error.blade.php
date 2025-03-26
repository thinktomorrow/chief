@props([
    'rule' => null,
])

@if ($rule)
    <div {{ $attributes->merge(['data-slot' => 'error']) }}>
        @if (isset($errors))
            @error($rule)
                <x-chief::callout size="sm" variant="red" class="px-2.5 py-1">
                    {{ ucfirst($message) }}
                </x-chief::callout>
            @enderror
        @endif

        <x-chief::callout data-error-placeholder="{{ $rule }}" size="sm" variant="red" class="hidden px-2.5 py-1">
            <div data-error-placeholder-content></div>
        </x-chief::callout>
    </div>
@endif
