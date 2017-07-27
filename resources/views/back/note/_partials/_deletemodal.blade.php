<!-- Admin delete note modal -->
<div id="remove-note-modal-{{$note->id}}" class="popup-basic admin-form mfp-with-anim mfp-hide">
    <div class="panel">
        <div class="panel-heading">
              <span class="panel-title">
                <i class="fa fa-remove"></i>Deze note verwijderen?</span>
        </div>
        <div class="panel-body">

            Hiermee zal u <em>{{ $note->content }}</em> permanent verwijderen. <br><br>Bent u zeker?
            <hr>

            <div class="text-center">
                <form action="{{ route('notes.destroy', $note->id) }}" method="POST" class="admin-form">
                    {!! csrf_field() !!}
                    <input type="hidden" name="_method" value="DELETE">
                    <button class="btn btn-danger btn-lg" type="submit">Wissen</button>
                </form>
            </div>
        </div>
    </div>
</div>
