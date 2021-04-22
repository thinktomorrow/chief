<div class="flex justify-between items-center">
    <span>De pagina staat nog in draft. </span>

    <form class="inline-block" action="@adminRoute('publish', $model)" method="POST">
        {{ csrf_field() }}
        <button type="submit" class="btn btn-primary cursor-pointer">Zet nu online</button>
    </form>
</div>
