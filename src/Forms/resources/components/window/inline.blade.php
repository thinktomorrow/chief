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
    {{ $attributes->class('flex flex-wrap sm:flex-nowrap items-start gap-3') }}
>
    <div class="w-full">
        {!! $slot !!}
    </div>

    @if($editUrl)
        <a data-sidebar-trigger href="{{ $editUrl }}" title="Aanpassen" class="inline-block mt-2 shrink-0">
            @if($icon)
                {!! $icon !!}
            @else
                <x-chief::icon-button icon="icon-edit"/>
            @endif
        </a>
    @endif
</div>
