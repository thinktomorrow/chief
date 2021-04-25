<div class="flex justify-between items-center">
    @if($linkForm->isAnyLinkOnline())
        <span>De pagina staat online. 👍</span>

        <form class="mb-0" action="@adminRoute('unpublish', $model)" method="POST">
            {{ csrf_field() }}
            <button type="submit" class="btn btn-error-outline">Haal offline</button>
        </form>
    @else
        <span class="mr-4">De pagina staat gepubliceerd maar zal zonder link nog niet bereikbaar zijn. Voeg hieronder nog een link toe!</span>

        <form class="mb-0" action="@adminRoute('unpublish', $model)" method="POST">
            {{ csrf_field() }}
            <button type="submit" class="btn btn-error-outline">Zet terug in draft</button>
        </form>
    @endif


</div>
