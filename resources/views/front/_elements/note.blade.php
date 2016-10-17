<?php

/**
 * Pass a note for presentation in the topbar. Disappears after a second.
 * pass to session as 'note' or with a flair of
 * note.danger, note.info,...
 *
 * e.g. If you want to display a note on the homepage, you could add a session flash to the
 * HomepageController::show -> Session::flash('note.danger','Beware of the dogs');
 */
if($note = \Illuminate\Support\Facades\Session::get('note',false))
{
    $flair = 'default';

    if(is_array($note)){
        $flair = key($note);
        $note = reset($note);
    }
}

?>

@if($note)
    <div id="note" class="{{ $flair }}">
        {!!  $note !!}
        <a class="close-note" aria-label="Close"><i class="fa fa-inverse fa-times-circle"></i></a>
    </div>
@endif
