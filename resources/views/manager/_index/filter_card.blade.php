@if(!isset($is_archive_index) || !$is_archive_index)
    @if($manager->filters()->anyRenderable())
        <x-chief::window title="Filter" class="card">
            <form method="GET" class="space-y-6">
                {!! $manager->filters()->render() !!}

                <div class="flex flex-wrap items-center gap-3">
                    <button class="btn btn-primary" type="submit">Filter</button>
                    <a
                        href="/{{ Route::getCurrentRoute()->uri() }}"
                        title="Reset alle filters"
                        class="link link-primary"
                    >
                        Reset alle filters
                    </a>
                </div>
            </form>
        </x-chief::window>
    @endif
@endif
