<modal id="delete-page-{{$page->id}}" class="large-modal" title=''>
    <form action="{{route('chief.back.pages.destroy', $page->id)}}" method="POST" id="delete-form-{{$page->id}}" slot>
        @method('DELETE')
        @csrf
        <div v-cloak>
            <div class="column-12">
                @if( ! $page->isPublished())
                    <h2 class="formgroup-label" slot="modal-header">Ok. Tijd om op te ruimen. <br>Ben je zeker?</h2>
                    <p>Type {{ $page->isDraft() || $page->isArchived() ? 'DELETE' : 'ARCHIVE' }} om dit item te verwijderen.</p>
                    <div class="input-group column-12">
                        <input name="deleteconfirmation" placeholder="{{ $page->isDraft() || $page->isArchived() ? 'DELETE' : 'ARCHIVE' }}" type="text" class="input inset-s">
                    </div>
                @else
                    <h2 class="formgroup-label">Opgelet! Een item dat online staat kan je niet zomaar verwijderen.</h2>
                    <p>Je kan het wel archiveren, zo kan je ze in de toekomst snel terug online plaatsen.</p>
                @endif
            </div>
        </div>
    </form>

    <div slot="modal-action-buttons">
        <button type="submit" class="btn btn-o-tertiary stack" data-submit-form="delete-form-{{$page->id}}">{{ $page->isDraft() || $page->isArchived() ? 'Verwijder' : 'Archiveer' }} dit artikel</button>
    </div>
</modal>
