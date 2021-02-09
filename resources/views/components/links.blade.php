<div data-links-component class="flex flex-col border border-grey-50">

    <div class="p-2 text-success">
        @unless($linkForm->exist())
            Geen huidige links
        @else
            @foreach($linkForm->links() as $locale => $links)
                @if($links->current)
                    <span>{{ $locale }}: <a target="_blank" rel="noopener" href="{{ $links->url }}">{{ $links->current->slug }}</a></span>
                @endif
            @endforeach
        @endunless
    </div>

    <div class="p-2">
        <a data-sidebar-links-edit class="underline" href="@adminRoute('links-edit', $model)">aanpassen</a>
    </div>
</div>

