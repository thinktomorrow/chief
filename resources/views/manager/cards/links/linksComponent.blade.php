<div data-links-component>
    <x-chief-card
        class="{{ isset($class) ? $class : '' }}"
        title="Visibiliteit"
        :editRequestUrl="$manager->route('links-edit', $model)"
        type="links">
        <div class="flex items-center space-x-2">
            <span class="font-medium">Status:</span>
            <span class="font-medium text-success">Online</span>
        </div>

        <div class="space-y-1">
            @unless($linkForm->exist())
                <p>Geen huidige links</p>
            @else
                @foreach($linkForm->links() as $locale => $links)
                    @if($links->current)
                        <div class="flex items-center space-x-3">
                            <span class="bg-grey-150 font-semibold text-grey-700 py-1 px-2 rounded">{{ strtoupper($locale) }}</span>

                            <a class="link link-primary" target="_blank" rel="noopener" href="{{ $links->url }}">
                                <x-link-label type="external-link" position="append">/{{ $links->current->slug }}</x-link-label>
                            </a>
                        </div>
                    @endif
                @endforeach
            @endunless
        </div>
    </x-chief-card>
</div>
