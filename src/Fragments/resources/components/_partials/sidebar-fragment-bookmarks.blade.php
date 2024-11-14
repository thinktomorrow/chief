@if ($owner && $model instanceof \Thinktomorrow\Chief\Fragments\Assistants\HasBookmark)
    <div class="flex flex-wrap items-center gap-2">
        <span class="label label-grey">#{{ $model->getBookmark() }}</span>

        @if ($owner instanceof \Thinktomorrow\Chief\Site\Visitable\Visitable)
            <a
                href="{{ $owner->url() }}#{{ $model->getBookmark() }}"
                title="Ga naar dit fragment op de website"
                target="_blank"
                rel="noopener"
                class="link link-primary"
            >
                <x-chief::button>
                    <svg><use xlink:href="#icon-external-link"></use></svg>
                </x-chief::button>
            </a>

            <x-chief::copy-button
                :content="'#'.$model->getBookmark()"
                successContent="#{{ $model->getBookmark() }} gekopieerd!"
            >
                <x-chief::button>
                    <svg><use xlink:href="#icon-link"></use></svg>
                </x-chief::button>
            </x-chief::copy-button>
        @endif
    </div>
@endif
