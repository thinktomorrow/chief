@if ($owner && $model instanceof \Thinktomorrow\Chief\Fragments\HasBookmark)
    <div class="flex flex-wrap items-center gap-2">
        <span class="label label-grey">#{{ $model->getBookmark() }}</span>

        @if ($owner instanceof \Thinktomorrow\Chief\Site\Visitable\Visitable)
            <a
                href="{{ $owner->url() }}#{{ $model->getBookmark() }}"
                title="Ga naar dit fragment op de website"
                target="_blank"
                rel="noopener"
                class="link link-blue"
            >
                <x-chief::button>
                    <svg>
                        <use xlink:href="#icon-external-link"></use>
                    </svg>
                </x-chief::button>
            </a>

            <x-chief::button
                x-copy="{
                    content: '#{{ $model->getBookmark() }}',
                    successContent: '#{{ $model->getBookmark() }} gekopieerd!'
                }"
            >
                <x-chief::icon.link />
            </x-chief::button>
        @endif
    </div>
@endif
