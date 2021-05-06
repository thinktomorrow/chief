<div data-links-component>
    <x-chief-card
        class="{{ isset($class) ? $class : '' }}"
        title="Permalink"
        :editRequestUrl="$manager->route('links-edit', $model)"
        type="links"
    >
        <div class="space-y-2">
            @unless($linkForm->exist())
                <p class="text-grey-700">
                    <a class="block link link-primary" data-sidebar-trigger="links" href="{{ $manager->route('links-edit', $model) }}">Voeg een eerste link toe</a>
                </p>
            @else
                @foreach($linkForm->links() as $locale => $link)
                    @if($link->current)
                        <div class="flex items-start space-x-4">
                            @if(count(config('chief.locales')) > 1)
                                <span class="flex-shrink-0 w-8 px-0 text-sm text-center label label-grey-light">{{ $locale }}</span>
                            @endif

                            <a class="mt-0.5 space-x-1 link link-primary underline" target="_blank" rel="noopener" href="{{ $link->url }}" style="word-break: break-word;">
                                {{ $link->url }}
                            </a>
                        </div>
                    @endif
                @endforeach
            @endunless
        </div>
    </x-chief-card>
</div>
