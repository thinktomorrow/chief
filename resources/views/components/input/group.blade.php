@props([
    'rule' => null,
    'innerClass' => 'space-y-1',
])

<div data-asyncform-group data-asyncform-rule="{{ $rule }}" {{ $attributes }}>
    <div class="{{ $innerClass }}">
        {{ $slot }}
    </div>

    <x-chief::input.error :rule="$rule" />
</div>
