@props([
    'rule' => null,
])

@if ($rule)
    @error($rule)
        <x-chief::inline-notification type="error" class="mt-2">
            {{ ucfirst($message) }}
        </x-chief::inline-notification>
    @enderror

    <x-chief::inline-notification data-error-placeholder="{{ $rule }}" type="error" class="hidden mt-2">
        <div data-error-placeholder-content></div>
    </x-chief::inline-notification>
@endif
