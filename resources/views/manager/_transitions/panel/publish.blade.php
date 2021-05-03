<div class="space-y-3 prose prose-dark">
    <p>De pagina staat nog in draft.</p>

    <form action="@adminRoute('publish', $model)" method="POST">
        {{ csrf_field() }}

        <button type="submit" class="btn btn-primary">Zet nu online</button>
    </form>
</div>
