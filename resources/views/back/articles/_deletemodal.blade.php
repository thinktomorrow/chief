<!-- Admin Form Popup -->
<div id="remove-article-modal" class="popup-basic admin-form mfp-with-anim mfp-hide">
    <div class="panel">
        <div class="panel-heading text-center">
              <span class="panel-title text-danger">Verwijder dit artikel?</span>
        </div>
        <div class="panel-body text-center">
            Dit zal het volgende artikel permanent verwijderen:<br>
            <em>{{ $article->title }}</em>.
            <br><br>Are you sure?
        </div>
        <div class="panel-footer">
            <div class="text-center">
                <form action="{{ route('back.articles.destroy',$article->getKey()) }}" method="POST" class="admin-form">
                    {!! csrf_field() !!}
                    <input type="hidden" name="_method" value="DELETE">
                    <button class="btn btn-danger btn-lg" type="submit">JA, verwijder artikel</button>
                </form>
            </div>
        </div>

    </div>
    <!-- end: .panel -->
</div>
<!-- end: .admin-form -->
