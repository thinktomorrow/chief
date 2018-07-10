<options-dropdown class="inline-block">
    <div class="inset-s" v-cloak>

        @if(\Illuminate\Support\Facades\Route::currentRouteName() !== 'chief.back.pages.edit')
            <a href="{{ route('chief.back.pages.edit',$page->getKey()) }}" class="block squished-s --link-with-bg">Aanpassen</a>
        @endif

        @if($page->isPublished())
            <a data-submit-form="draftForm-{{$page->id}}" class="block squished-s --link-with-bg">Haal offline</a>

            <form class="--hidden" id="draftForm-{{$page->id}}" action="{{ route('chief.back.pages.draft', $page->id) }}" method="POST">
                {{ csrf_field() }}
                <button type="submit">Unpublish</button>
            </form>

            <hr class="stack-s">

            <a data-submit-form="archiveForm-{{$page->id}}" class="block squished-s text-error --link-with-bg">Archiveer '{{ $page->title }}'</a>

            <form class="--hidden" id="archiveForm-{{$page->id}}" action="{{ route('chief.back.pages.archive', $page->id) }}" method="POST">
                {{ csrf_field() }}
                <input type="hidden" name="_method" value="PUT">
                <button type="submit">Archive</button>
            </form>

        @elseif($page->isDraft())
            <a data-submit-form="publishForm-{{$page->id}}" class="block squished-s --link-with-bg">Zet online</a>

            <form class="--hidden" id="publishForm-{{$page->id}}" action="{{ route('chief.back.pages.publish', $page->id) }}" method="POST">
                {{ csrf_field() }}
                <button type="submit">Publish</button>
            </form>

            <hr class="stack-s">

            <a @click="showModal('delete-page-{{$page->id}}')" class="block squished-s text-error --link-with-bg">
            Verwijderen
            </a>

        @elseif($page->isArchived())
            <a data-submit-form="draftForm-{{$page->id}}" class="block squished-s --link-with-bg">Opnieuw openen als draft</a>

            <form class="--hidden" id="draftForm-{{$page->id}}" action="{{ route('chief.back.pages.draft', $page->id) }}" method="POST">
                {{ csrf_field() }}
                <button type="submit">Unpublish</button>
            </form>

            <hr class="stack-s">

            <a @click="showModal('delete-page-{{$page->id}}')" class="block squished-s text-error --link-with-bg">
            Verwijderen
            </a>
        @endif
    </div>
</options-dropdown>