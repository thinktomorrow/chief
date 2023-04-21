@if(!isset($is_archive_index) || !$is_archive_index)
    @if($manager->filters()->anyRenderable())
        <x-chief::window title="Filter" class="card">
            <form method="GET" class="space-y-6">
                {!! $manager->filters()->render() !!}

                <div class="flex flex-wrap gap-3">
                    <button class="btn btn-primary" type="submit">Filter</button>
                    <input type="reset" value="Reset" class="btn btn-grey btn-sm"/>
                </div>
            </form>
        </x-chief::window>
    @endif
@endif
