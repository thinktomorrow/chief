<div class="row bg-white inset panel panel-default stack">
    <div class="column-9">
            <a class="text-black bold" href="{{ route('chief.back.pages.edit',$page->getKey()) }}">
                {{ $page->getTranslationFor('title') }}
            </a>
            <div>
                <span class="text-subtle">Aangepast op: {{ $page->updated_at->format('d/m/Y') }}</span>
                <a title="Bekijk {{ $page->title }}" href="{{ $page->previewUrl() }}" target="_blank" class="font-s text-border"><em>Preview</em></a>
            </div>
            <div class="stack-s">
                {{ teaser($page->content,250,'...') }}
            </div>
    </div>
    <div class="column-3 text-right">
        <options-dropdown class="inline-block">
            <div class="inset-s" v-cloak>
                <a href="{{ route('chief.back.pages.edit',$page->getKey()) }}" class="block squished-s --link-with-bg">Aanpassen</a>

                @if($page->isPublished())
                    <a data-submit-form="draftForm-{{$page->id}}" class="block squished-s --link-with-bg">Haal offline</a>

                    <form class="--hidden" id="draftForm-{{$page->id}}" action="{{ route('chief.back.pages.draft', $page->id) }}" method="POST">
                        {{ csrf_field() }}
                        <button type="submit">Unpublish</button>
                    </form>
                @elseif($page->isDraft())
                    <a data-submit-form="publishForm-{{$page->id}}" class="block squished-s --link-with-bg">Zet online</a>

                    <form class="--hidden" id="publishForm-{{$page->id}}" action="{{ route('chief.back.pages.publish', $page->id) }}" method="POST">
                        {{ csrf_field() }}
                        <button type="submit">Publish</button>
                    </form>
                @endif

                <hr class="stack-s">

                <a @click="showModal('delete-page-{{$page->id}}')" class="block squished-s text-error --link-with-bg">
                    Gooi {{ $page->collectionDetails()->singular }} weg
                </a>
            </div>
        </options-dropdown>
    </div>
</div>