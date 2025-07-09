@php
    $title = 'Nieuwe ' . $resource->getLabel() . ' aanmaken';
@endphp

<x-chief::page.template :title="$title" container="md">
    <x-slot name="header">
        <x-chief::page.header
            :breadcrumbs="[
                [
                    'label' => $resource->getIndexTitle(),
                    'url' => $manager->route('index'),
                    'icon' => $resource->getNavItem()?->icon()
                ],
                $title
            ]"
        />
    </x-slot>

    <x-chief::window>
        <form id="createForm" method="POST" action="@adminRoute('store')" enctype="multipart/form-data" role="form">
            @csrf

            <x-chief::form.fieldset class="w-full space-y-3">
                <x-chief::form.label for="allowed_sites" required>
                    Op welke sites wil je de nieuwe pagina tonen?
                </x-chief::form.label>

                @foreach (\Thinktomorrow\Chief\Sites\ChiefSites::all() as $site)
                    <label
                        for="{{ $site->locale }}"
                        @class([
                            'flex items-start gap-3 rounded-xl border border-grey-200 p-4',
                            '[&:has(input[type=checkbox]:checked)]:border-blue-200 [&:has(input[type=checkbox]:checked)]:bg-blue-50',
                        ])
                    >
                        <x-chief::form.input.checkbox
                            wire:model="allowed_sites"
                            id="{{ $site->locale }}"
                            value="{{ $site->locale }}"
                            class="shrink-0"
                        />

                        <div class="flex grow items-start justify-between gap-2">
                            <div class="space-y-2">
                                <p class="font-medium leading-5 text-grey-700">
                                    {{ $site->name }} ({{ $site->shortName }})
                                </p>

                                <p class="leading-5 text-grey-500">{{ $site->url }}</p>
                            </div>
                        </div>
                    </label>
                @endforeach
            </x-chief::form.fieldset>

            @foreach ($layout->filterByNotTagged(['not-on-model-create', 'not-on-create'])->getComponents() as $component)
                @if ($component instanceof \Thinktomorrow\Chief\Forms\Layouts\Form)
                    {{ $component->displayAsInlineForm()->render() }}
                @else
                    {{ $component->render() }}
                @endif
            @endforeach

            <x-chief::button data-slot="form-group" type="submit" variant="blue">Aanmaken</x-chief::button>
        </form>
    </x-chief::window>

</x-chief::page.template>
