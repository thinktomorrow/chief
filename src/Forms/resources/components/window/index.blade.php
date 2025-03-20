@props([
    'editUrl' => null,
    'refreshUrl' => null,
    'tags' => null,
    'title' => null,
    'labels' => null,
    'actions' => null,
    'description' => null,
])

<x-chief::window
    data-form
    data-form-url="{{ $refreshUrl }}"
    data-form-tags="{{ $tags }}"
    :title="$title"
    :labels="$labels"
    :description="$description"
    {{ $attributes }}
>
    @if($editUrl || $actions)
        <x-slot name="actions">
            @if($editUrl)
                <x-chief-table::button data-sidebar-trigger href="{{ $editUrl }}" title="Aanpassen" size="sm" variant="grey">
                    <x-chief::icon.quill-write />
                </x-chief-table::button>
            @endif

            @if($actions)
                {!! $actions !!}
            @endif
        </x-slot>
    @endif

    @if($slot->isNotEmpty())
        <div>
            {!! $slot !!}
        </div>
    @endisset
</x-chief::window>
