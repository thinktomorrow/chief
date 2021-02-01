<div class="row gutter">
    @foreach($models as $model)
        <div class="column-4">
            <div class="bg-grey-100 p-4">
                <h3 class="w-full">{{ $model->title }}</h3>
                <p class="w-full">{{ $model->{'content:nl'} }}</p>
            </div>
        </div>
    @endforeach
</div>
<div>
    <a data-fetch href="@adminRoute('edit', $model)" class="btn btn-primary">Aanpassen</a>
    <a href="" class="btn btn-information">herschikken</a>
</div>

