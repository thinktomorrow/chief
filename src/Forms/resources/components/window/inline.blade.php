@props([
    'editUrl' => null,
    'refreshUrl' => null,
    'tags' => null,
    'icon' => null,
])

<div
    data-form
    data-form-url="{{ $refreshUrl }}"
    data-form-tags="{{ $tags }}"
    {{ $attributes->class('flex flex-wrap items-end gap-4 sm:flex-nowrap') }}
>
    <div class="w-full">
        {!! $slot !!}
    </div>

    @if ($editUrl)
        <a data-sidebar-trigger href="{{ $editUrl }}" title="Aanpassen" class="inline-block shrink-0 sm:mb-0.5">
            @if ($icon)
                {!! $icon !!}
            @else
                <x-chief-table::button color="white" size="sm">
                    <x-chief::icon.quill-write />
                </x-chief-table::button>
            @endif
        </a>
    @endif
</div>
