@adminCan('links-edit', $model)
@php
    $linkForm = Thinktomorrow\Chief\Site\Urls\Form\LinkForm::fromModel($model);
@endphp

<x-chief-form::window
    title="Links"
    :edit-url="$manager->route('links-edit', $model)"
    :refresh-url="$manager->route('links-window', $model)"
    tags="status,links"
>
    <div class="space-y-2">
        @unless ($linkForm->exist())
            <x-chief::link
                variant="blue"
                href="{{ $manager->route('links-edit', $model) }}"
                data-sidebar-trigger="links"
            >
                <x-chief::icon.plus-sign />
                <span>Voeg een eerste link toe</span>
            </x-chief::link>
        @else
            @foreach ($linkForm->links() as $locale => $link)
                @if ($link->current)
                    <div class="flex items-start gap-4">
                        @if (count(\Thinktomorrow\Chief\Sites\Locales\ChiefLocales::locales()) > 1)
                            <span
                                class="label label-grey label-sm mt-1 inline-flex w-8 shrink-0 items-center justify-center p-0"
                            >
                                {{ $locale }}
                            </span>
                        @endif

                        <a
                            href="{{ $link->url }}"
                            title="Bekijk deze pagina"
                            class="link {{ $link->is_online ? 'link-primary' : 'link-warning' }} mt-0.5 space-x-1 underline"
                            style="word-break: break-word"
                        >
                            {{ str_replace(['http://', 'https://'], '', $link->url) }}
                        </a>
                    </div>
                @endif
            @endforeach
        @endunless
    </div>
</x-chief-form::window>
@endAdminCan
