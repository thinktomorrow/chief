@if($owner && $model instanceof \Thinktomorrow\Chief\Fragments\Assistants\HasBookmark)
    <div class="flex flex-wrap items-center gap-2">
        <span class="label label-grey">#{{ $model->getBookmark() }}</span>

        @if($owner instanceof \Thinktomorrow\Chief\Site\Visitable\Visitable)
            <a
                href="{{ $owner->url() }}#{{ $model->getBookmark() }}"
                title="Ga naar dit fragment op de website"
                target="_blank"
                rel="noopener"
                class="link link-primary"
            >
                <x-chief::icon-button icon="icon-external-link" />
            </a>

            <span
                data-copy-to-clipboard="bookmark"
                data-copy-value="{{ $owner->url() }}#{{ $model->getBookmark() }}"
                data-copy-success-content="Gekopiëerd!"
                title="Kopiëer de link naar deze bookmark"
                class="text-sm cursor-pointer link link-primary"
            >
                <x-chief::icon-button icon="icon-link" />
            </span>
        @endif
    </div>
@endif
