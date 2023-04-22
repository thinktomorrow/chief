@props([
    'aside'
])

<div {{ $attributes->merge(['class' => '']) }}>
    <div class="row-start-stretch">
        <div @class(['w-full space-y-6', 'lg:w-2/3' => isset($aside)])>
            {{ $slot }}
        </div>

        @isset ($aside)
            <div class="w-full space-y-6 lg:border-l lg:w-1/3 lg:border-grey-200">
                {{ $aside }}
            </div>
        @endisset
    </div>
</div>
