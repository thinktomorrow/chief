<div data-links-component>
    <x-chief-card
        class="{{ isset($class) ? $class : '' }}"
        title="Links"
        :editRequestUrl="$manager->route('links-edit', $model)"
        sidebarTrigger="data-sidebar-trigger=links"
    >
        <div class="space-y-2">
            @unless($linkForm->exist())
                <a
                    class="link link-primary"
                    data-sidebar-trigger="links"
                    href="{{ $manager->route('links-edit', $model) }}"
                >
                    <x-chief-icon-label type="add">Voeg een eerste link toe</x-chief-icon-label>
                </a>
            @else
                @foreach($linkForm->links() as $locale => $link)
                    @if($link->current)
                        <div class="flex items-start space-x-4">
                            @if(count(config('chief.locales')) > 1)
                                <span class="flex-shrink-0 w-8 px-0 text-sm text-center label label-grey-light">{{ $locale }}</span>
                            @endif

                            <a class="mt-0.5 space-x-1 link {{ $link->is_online ? 'link-primary' : 'link-warning' }} underline" target="_blank" rel="noopener" href="{{ $link->url }}" style="word-break: break-word;">
                                {{ str_replace(['http://','https://'], '', $link->url) }}
                            </a>
                        </div>
                    @endif
                @endforeach
            @endunless
        </div>
    </x-chief-card>
</div>
