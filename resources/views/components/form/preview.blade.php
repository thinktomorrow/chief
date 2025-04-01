@props([
    'label' => null,
    'description' => null,
    'size' => 'base',
])

@php
    $containerClass = match ($size) {
        'base' => '@xl:flex-nowrap',
        'lg' => '@2xl:flex-nowrap',
        default => '@xl:flex-nowrap',
    };

    $labelClass = match ($size) {
        'base' => 'w-full @xl:w-48',
        'lg' => 'w-full @2xl:w-64',
        default => 'w-full @xl:w-48',
    };
@endphp

<div data-slot="form-preview" @class(['@container' => $label || $description])>
    <div @class([
        'flex flex-wrap items-start gap-x-4 gap-y-1',
        $containerClass,
    ])>
        @if ($label || $description)
            <div @class([
                'shrink-0 space-y-2',
                $labelClass,
            ])>
                @if ($label)
                    <p class="mt-0.5 text-sm/5 font-medium text-grey-500">
                        {{ $label }}
                    </p>
                @endif

                @if ($description)
                    <x-chief::form.description class="text-grey-700">
                        {{ $description }}
                    </x-chief::form.description>
                @endif
            </div>
        @endif

        <div class="grow">
            {{ $slot }}
        </div>
    </div>
</div>
