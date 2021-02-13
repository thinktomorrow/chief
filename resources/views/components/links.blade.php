<div data-links-component class="flex flex-col space-y-6">

    <div>
        <h3>Links</h3>
        @unless($linkForm->exist())
            <p> Geen huidige links </p>
        @else
            @foreach($linkForm->links() as $locale => $links)
                @if($links->current)
                    <span>{{ $locale }}: <a target="_blank" rel="noopener" href="{{ $links->url }}">{{ $links->current->slug }}</a></span>
                @endif
            @endforeach
        @endunless
    </div>

    <div>
        <a
            data-sidebar-links-edit
            class="link link-primary"
            href="@adminRoute('links-edit', $model)"
        >
            <x-link-label type="edit">Aanpassen</x-link-label>
        </a>
    </div>
</div>
