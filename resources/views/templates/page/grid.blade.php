@props([
    'aside'
])

<div {{ $attributes->merge(['class' => 'container']) }}>
    <div class="row-start-start gutter-3">
        <div @class(['w-full space-y-6', 'lg:w-2/3' => isset($aside)])>
            {{ $slot }}
        </div>

        @isset ($aside)
            <div class="w-full space-y-6 lg:w-1/3">
                {{ $aside }}
            </div>
        @endisset
    </div>
</div>
