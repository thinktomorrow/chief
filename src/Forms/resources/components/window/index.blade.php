@props([
    'editUrl' => null,
    'refreshUrl' => null,
    'tags' => null,
    'title' => null,
    'labels' => null,
    'buttons' => null,
])

<div data-form data-form-url="{{ $refreshUrl }}" data-form-tags="{{ $tags }}" {{ $attributes->class('space-y-6') }}>
    {{-- Window header --}}
    @if($title || $labels || $editUrl)
        <div class="flex justify-end space-x-4">
            <div class="w-full space-x-1">
                @if($title)
                    <span class="text-lg display-base display-dark">
                        {!! $title !!}
                    </span>
                @endif

                @if($labels)
                    <span class="align-bottom with-xs-labels">
                        {!! $labels !!}
                    </span>
                @endif
            </div>

            @if($editUrl || $buttons)
                <div class="shrink-0">
                    @if($editUrl)
                        <a data-sidebar-trigger href="{{ $editUrl }}" title="Aanpassen">
                            <x-chief-icon-button icon="icon-edit" />
                        </a>
                    @endif

                    @if($buttons)
                        {!! $buttons !!}
                    @endif
                </div>
            @endif
        </div>
    @endif

    {{-- Window content --}}
    @if($slot->isNotEmpty())
        <div>
            {!! $slot !!}
        </div>
    @endisset
</div>
