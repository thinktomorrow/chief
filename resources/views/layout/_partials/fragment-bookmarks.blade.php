@php

    $isBookmarkEditable = false;
    $formId = null;

    // Hacky way to connect with the first (and usually the only) form in the fragment sidebar
    $availableForms = $forms->get();

    if(count($availableForms) > 0) {
        $isBookmarkEditable = true;
        $formId = $availableForms[0]->getElementId();
    }

@endphp

@if($owner && $model instanceof \Thinktomorrow\Chief\Fragments\Assistants\HasBookmark)
    <div class="flex flex-wrap items-center gap-2">
        <span data-fragment-bookmark-label class="label label-grey">
            #{{ $model->getBookmark() }}
        </span>

        @if($owner instanceof \Thinktomorrow\Chief\Site\Visitable\Visitable)
                <div data-fragment-bookmark-form class="hidden form-light grow">
                    <x-chief::input.text
                        data-fragment-bookmark-input
                        form="{{ $formId }}"
                        id="bookmark_label"
                        name="bookmark_label"
                        value="{{ old('bookmark_label', $model->getBookmark()) }}"
                        class="px-1 py-0.5"
                    />
                </div>

            @if($isBookmarkEditable)
                {{-- if this is clicked, it should toggle to a cancellation button --}}
                <span class="cursor-pointer">
                    <x-chief::icon-button data-fragment-bookmark-edit-button icon="icon-edit" color="grey"/>

                    <x-chief::icon-button data-fragment-bookmark-undo-button color="grey" class="hidden">
                        <svg width="18" height="18" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" />
                        </svg>
                    </x-chief::icon-button>

                    <x-chief::icon-button data-fragment-bookmark-confirm-button color="grey" class="hidden">
                        <svg width="18" height="18" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                    </x-chief::icon-button>
                </span>
            @endif

            <a
                data-fragment-bookmark-external-link-button
                href="{{ $owner->url() }}#{{ $model->getBookmark() }}"
                title="Ga naar dit fragment op de website"
                target="_blank"
                rel="noopener"
                class="cursor-pointer"
            >
                <x-chief::icon-button icon="icon-external-link" color="grey" />
            </a>

            <span
                data-fragment-bookmark-copy-button
                data-copy-to-clipboard="bookmark"
                data-copy-value="{{ $owner->url() }}#{{ $model->getBookmark() }}"
                data-copy-success-content="Gekopiëerd!"
                title="Kopiëer de link naar deze bookmark"
                class="text-sm cursor-pointer body-dark"
            >
                <x-chief::icon-button icon="icon-link" color="grey" />
            </span>
        @endif
    </div>
@endif
