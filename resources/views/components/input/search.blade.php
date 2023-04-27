@props([
    'autofocus' => false
])

<div class="relative flex-grow">
    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
        <svg class="w-5 h-5 text-grey-400"><use xlink:href="#icon-magnifying-glass"></use></svg>
    </div>

    <input type="text" {{ $attributes->class('form-input-field pl-10') }} {!! $autofocus ? 'autofocus' : null !!}>
</div>
