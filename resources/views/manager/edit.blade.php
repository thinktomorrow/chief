<x-chief::page>
    <x-slot name="header">
        <div class="flex flex-wrap items-end justify-between gap-6">
            <x-chief-form::forms id="pagetitle" />

            <tabs class="-mb-3">
                @foreach(config('chief.locales') as $locale)
                    <tab v-cloak id="{{ $locale }}-translatable-fields" name="{{ $locale }}"></tab>
                @endforeach
            </tabs>
        </div>
    </x-slot>

    <x-chief-form::forms position="main" />

    @adminCan('fragments-index', $model)
        <x-chief::fragments :owner="$model" />
    @endAdminCan

    <x-chief-form::forms position="main-bottom" />

    <x-slot name="aside">
        <x-chief-form::forms position="aside-top" />
        <x-chief::window.status />
        <x-chief::window.links />
        <x-chief-form::forms position="aside" />
    </x-slot>
</x-chief::page>
