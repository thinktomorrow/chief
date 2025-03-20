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
    {{ $attributes->class('flex items-start gap-3') }}
>
    <div class="w-full">
        {!! $slot !!}
    </div>

    @if ($editUrl)
        <x-chief-table::button
            data-sidebar-trigger
            href="{{ $editUrl }}"
            title="Aanpassen"
            size="sm"
            variant="grey"
            class="mt-[0.3125rem] shrink-0"
        >
            <x-chief::icon.quill-write />
        </x-chief-table::button>
    @endif
</div>
