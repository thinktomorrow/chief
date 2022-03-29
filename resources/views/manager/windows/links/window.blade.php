@adminCan('links-edit', $model)
    @php
        $linkForm = \Thinktomorrow\Chief\Site\Urls\Form\LinkForm::fromModel($model);
    @endphp

    <x-chief-form::window
        title="Links"
        :edit-url="$manager->route('links-edit', $model)"
        :refresh-url="$manager->route('links-window', $model)"
        class="card"
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
                        <div class="flex items-start gap-4">
                            @if(count(config('chief.locales')) > 1)
                                <span class="inline-flex items-center justify-center w-8 p-0 mt-1 shrink-0 label label-grey label-sm">
                                    {{ $locale }}
                                </span>
                            @endif

                            <a
                                href="{{ $link->url }}"
                                title="Bekijk deze pagina"
                                target="_blank"
                                rel="noopener"
                                class="mt-0.5 space-x-1 link {{ $link->is_online ? 'link-primary' : 'link-warning' }} underline"
                                style="word-break: break-word;"
                            >
                                {{ str_replace(['http://','https://'], '', $link->url) }}
                            </a>
                        </div>
                    @endif
                @endforeach
            @endunless
        </div>
    </x-chief-form::window>
@endAdminCan
