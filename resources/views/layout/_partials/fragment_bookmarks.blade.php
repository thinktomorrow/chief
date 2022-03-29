@if($owner && $model instanceof \Thinktomorrow\Chief\Fragments\Assistants\HasBookmark)
    <div class="flex items-center group">
        {{-- bookmark for this fragment --}}
        <span class="mr-2 label label-grey">#{{ $model->getBookmark() }}</span>

        <div class="inline-flex items-center gutter-1">
            @if($owner instanceof \Thinktomorrow\Chief\Site\Visitable\Visitable)
                <span class="scale-0 group-hover:scale-100 transition-150">
                    <a
                        href="{{ $owner->url() }}#{{ $model->getBookmark() }}"
                        target="_blank"
                        class="link link-primary -mt-0.5"
                    >
                        <x-chief-icon-label icon="icon-external-link" size="18"></x-chief-icon-label>
                    </a>
                </span>

                <span class="scale-0 group-hover:scale-100 transition-150">
                    <span
                        data-copy-to-clipboard="bookmark"
                        data-copy-value="{{ $owner->url() }}#{{ $model->getBookmark() }}"
                        data-copy-success-content="Gekopiëerd!"
                        title="copy link"
                        class="leading-none cursor-pointer link link-primary"
                    >
                        <x-chief-icon-label icon="icon-link" size="18"></x-chief-icon-label>
                    </span>
                </span>
            @endif
        </div>
    </div>
@endif
