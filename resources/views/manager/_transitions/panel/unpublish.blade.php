<div class="space-y-3 prose prose-spacing prose-dark">
    @if($linkForm->isAnyLinkOnline())
        <p>De pagina staat online. 👍</p>

        <form action="@adminRoute('unpublish', $model)" method="POST">
            {{ csrf_field() }}

            <button type="submit" class="btn btn-error-outline">Haal offline</button>
        </form>
    @else
        <p>De pagina staat klaar voor publicatie maar er ontbreekt nog een link. Voeg hieronder nog een link toe!</p>

        <form action="@adminRoute('unpublish', $model)" method="POST">
            {{ csrf_field() }}

            <button type="submit" class="btn btn-error-outline">Zet terug offline</button>
        </form>
    @endif
</div>
