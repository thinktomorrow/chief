@props([
    'title' => null,
    'labels' => null,
    'editUrl' => null,
    'refreshUrl' => null,
    'icon' => null,
    'tags' => null,
])

<div data-form data-form-url="{{ $refreshUrl }}" data-form-tags="{{ $tags }}" {{ $attributes }}>
    <div class="bg-white rounded-2xl shadow-window">
        <div class="p-6">
            <div class="{{ $slot->isNotEmpty() ? 'mb-6' : null }}">
                <div class="flex justify-end space-x-4">
                    <div class="w-full space-x-1 mt-0.5">
                        @isset($title)
                            <span class="text-lg display-base display-dark">
                                {!! $title !!}
                            </span>
                        @endisset

                        @isset($labels)
                            <span class="align-bottom with-xs-labels">
                                {!! $labels !!}
                            </span>
                        @endisset
                    </div>

                    @if($editUrl)
                        <a
                            data-sidebar-trigger
                            href="{{ $editUrl }}"
                            title="Aanpassen"
                            class="flex-shrink-0"
                        >
                            @isset($icon)
                                {!! $icon !!}
                            @else
                                <x-chief-icon-button icon="icon-edit" />
                            @endisset
                        </a>
                    @endif
                </div>
            </div>

            @if($slot->isNotEmpty())
                <div>
                    {!! $slot !!}
                </div>
            @endisset
        </div>
    </div>
</div>
