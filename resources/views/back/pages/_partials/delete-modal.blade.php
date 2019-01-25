<modal id="delete-page-{{$page->id}}" class="large-modal" title=''>
    <form action="{{route('chief.back.pages.destroy', $page->id)}}" method="POST" id="delete-page-form-{{$page->id}}" slot>
        @method('DELETE')
        @csrf
        <div v-cloak>
            @if( ! $page->isPublished() || $page->isArchived())
                <h2 class="formgroup-label" slot="modal-header">Ok. Tijd om op te ruimen. <br>Ben je zeker?</h2>
                <p>Type {{ $page->isDraft() || $page->isArchived() ? 'DELETE' : 'ARCHIVE' }} om dit item te verwijderen.</p>
                <div class="input-group stack">
                    <input data-delete-confirmation name="deleteconfirmation" placeholder="{{ $page->isDraft() || $page->isArchived() ? 'DELETE' : 'ARCHIVE' }}" type="text" class="input inset-s" autocomplete="off">
                </div>
            @else
                <h2 class="formgroup-label">Een item dat online staat kan je niet zomaar verwijderen.</h2>
                <p>Je kan het wel archiveren, zo kan je ze in de toekomst snel terug online plaatsen.</p>
            @endif
        </div>
    </form>

    <div slot="modal-action-buttons">
        <button type="button" class="btn btn-o-tertiary stack" data-submit-form="delete-page-form-{{$page->id}}">{{ $page->isDraft() || $page->isArchived() ? 'Verwijder' : 'Archiveer' }} dit artikel</button>
    </div>
</modal>
