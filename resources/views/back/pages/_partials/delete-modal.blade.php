<modal id="delete-page-{{$page->id}}" class="large-modal">
    <form action="{{route('back.pages.destroy', $page->id)}}" method="POST">
        @method('DELETE')
        @csrf
        <div v-cloak>
            <div class="column-12">
                <h2 class="formgroup-label">Ok. Tijd om op te ruimen. <br>Ben je zeker?  👍</h2>
                <p>Vul dan hier {{ $page->isDraft() || $page->isArchived() ? 'DELETE' : 'ARCHIVE' }} in om te verwijderen.</p>
                @if($page->isDraft() || $page->isArchived())
                    <div id="clone-12" class="input-group column">
                        <input name="deleteconfirmation" id="text" placeholder="{{ $page->isDraft() || $page->isArchived() ? 'DELETE' : 'ARCHIVE' }}" type="text" class="input inset-s">
                    </div>
                @endif
            </div>
        </div>
        <div slot="footer">
            <button type="submit" class="btn btn-o-tertiary">{{ $page->isDraft() || $page->isArchived() ? 'Verwijder' : 'Archiveer' }} dit artikel</button>
        </div>
    </form>
</modal>
