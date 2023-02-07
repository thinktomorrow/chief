@props([
    'rule' => null,
    'theme' => 'light',
    'innerClass' => 'space-y-1',
])

<div
    data-asyncform-group
    data-asyncform-rule="{{ $rule }}"
    {{ $attributes->class(['form-light' => $theme == 'light']) }}
>
    <div class="{{ $innerClass }}">
        {{ $slot }}
    </div>

    <x-chief::input.error :rule="$rule"/>
</div>
