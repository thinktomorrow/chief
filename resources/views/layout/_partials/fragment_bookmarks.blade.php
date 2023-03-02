@if($owner && $model instanceof \Thinktomorrow\Chief\Fragments\Assistants\HasBookmark)
    <div class="flex flex-wrap items-center gap-2">
        <span data-fragment-bookmark-label class="label label-grey">#{{ $model->getBookmark() }}</span>

        @if($owner instanceof \Thinktomorrow\Chief\Site\Visitable\Visitable)
            {{-- TODO(ben): This form should save the bookmark_label as a dynamic attribute, just like other fragment forms --}}
            <form data-fragment-bookmark-form action="#" method="PUT" class="hidden form-light">
                <x-chief::input.text
                    data-fragment-bookmark-input
                    id="bookmark_label"
                    name="bookmark_label"
                    value="{{ old('bookmark_label', $model->getBookmark()) }}"
                    class="w-auto px-1 py-0.5"
                />
            </form>

            {{-- if this is clicked, it should toggle to a cancellation button --}}
            <span class="cursor-pointer">
                <x-chief::icon-button
                    data-fragment-bookmark-edit-button
                    icon="icon-edit"
                    color="grey"
                />

                <x-chief::icon-button
                    data-fragment-bookmark-cancel-button
                    color="grey"
                    class="hidden"
                >
                    <svg width="18" height="18" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" />
                    </svg>
                </x-chief::icon-button>
            </span>

            <a
                href="{{ $owner->url() }}#{{ $model->getBookmark() }}"
                title="Ga naar dit fragment op de website"
                target="_blank"
                rel="noopener"
                class="cursor-pointer"
            >
                <x-chief::icon-button icon="icon-external-link" color="grey" />
            </a>

            <span
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
