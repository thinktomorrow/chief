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
    {{ $attributes->class('inline-flex items-center') }}
>
    {!! $slot !!}

    @if($editUrl)
        <a data-sidebar-trigger href="{{ $editUrl }}" title="Aanpassen" class="ml-3 shrink-0">
            @if($icon)
                {!! $icon !!}
            @else
                <x-chief-icon-button icon="icon-edit" />
            @endif
        </a>
    @endif
</div>
