@props([
    'editUrl' => null,
    'refreshUrl' => null,
    'tags' => null,
    'title' => null,
    'labels' => null,
    'icon' => null,
])

<div data-form data-form-url="{{ $refreshUrl }}" data-form-tags="{{ $tags }}" {{ $attributes }}>
    <div class="space-y-6 window">
        {{-- Window header --}}
        @if($title || $labels || $editUrl)
            <div class="flex justify-end space-x-4">
                <div class="w-full space-x-1">
                    @if($title)
                        <span class="text-xl display-base display-dark">
                            {!! $title !!}
                        </span>
                    @endif

                    @if($labels)
                        <span class="align-bottom with-xs-labels">
                            {!! $labels !!}
                        </span>
                    @endif
                </div>

                @if($editUrl)
                    <a data-sidebar-trigger href="{{ $editUrl }}" title="Aanpassen" class="shrink-0">
                        @if($icon)
                            {!! $icon !!}
                        @else
                            <x-chief-icon-button icon="icon-edit" />
                        @endif
                    </a>
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
</div>
