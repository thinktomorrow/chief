@if($manager->filters()->anyRenderable())
    <div class="card">
        <div class="w-full space-x-1 mt-0.5">
            <span class="text-lg display-base display-dark">
                Filter
            </span>
        </div>

        <form method="GET" class="space-y-6">
            {!! $manager->filters()->render() !!}

            <div>
                <button class="btn btn-primary" type="submit">Filter</button>
            </div>
        </form>
    </div>
@endif
