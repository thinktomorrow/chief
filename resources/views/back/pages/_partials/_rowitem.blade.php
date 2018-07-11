<div class="row bg-white inset panel panel-default stack">
    <div class="column-9">

            @if($page->isArchived())
                <span class="text-black bold" title="Een gearchiveerde {{ $page->collectionDetails()->singular }} kan niet worden bewerkt.">
                    {{ $page->getTranslationFor('title') }}
                </span>
            @else
                <a class="text-black bold" href="{{ route('chief.back.pages.edit',$page->getKey()) }}">
                    {{ $page->getTranslationFor('title') }}
                </a>
            @endif

            <div>
                <span class="text-subtle">Laatst aangepast op {{ $page->updated_at->format('d/m/Y') }}</span>

                @if($page->isPublished())
                    <span class="text-subtle">|</span>
                    <a title="Bekijk {{ $page->title }}" href="{{ $page->menuUrl() }}" target="_blank" class="text-border">Bekijk online</a>
                @elseif($page->isDraft())
                    <span class="text-subtle">|</span>
                    <a title="Bekijk {{ $page->title }}" href="{{ $page->previewUrl() }}" target="_blank" class="text-border">Bekijk preview</a>
                @endif
            </div>
            <div class="stack-s font-s">
                {{ teaser($page->content,250,'...') }}
            </div>
    </div>
    <div class="column-3 text-right">
        @include('chief::back.pages._partials.context-menu')
    </div>
</div>