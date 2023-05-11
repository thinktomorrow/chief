@props([
    'editUrl' => null,
    'refreshUrl' => null,
    'tags' => null,
    'title' => null,
    'labels' => null,
    'buttons' => null,
])

<x-chief::window
    data-form
    data-form-url="{{ $refreshUrl }}"
    data-form-tags="{{ $tags }}"
    :title="$title"
    :labels="$labels"
    {{ $attributes }}
>
    @if($editUrl || $buttons)
        <x-slot name="buttons">
            @if($editUrl)
                <a data-sidebar-trigger href="{{ $editUrl }}" title="Aanpassen">
                    <x-chief::icon-button icon="icon-edit" />
                </a>
            @endif

            @if($buttons)
                {!! $buttons !!}
            @endif
        </x-slot>
    @endif

    @if($slot->isNotEmpty())
        <div>
            {!! $slot !!}
        </div>
    @endisset
</x-chief::window>
