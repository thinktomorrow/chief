<x-chief::page>
    <x-slot name="header">
        <div class="flex flex-wrap items-end justify-between gap-6">
            @if($forms->has('pagetitle'))
                <x-chief-form::forms id="pagetitle" />
            @else
                <h1 class="h1 display-dark">{{ $resource->getPageTitle($model) }}</h1>
            @endif

            @if(count(config('chief.locales')) > 1)
                <tabs class="-mb-3">
                    @foreach(config('chief.locales') as $locale)
                        <tab v-cloak id="{{ $locale }}" name="{{ $locale }}"></tab>
                    @endforeach
                </tabs>
            @endif
        </div>
    </x-slot>

    <x-chief-form::forms position="main" />

    @adminCan('fragments-index', $model)
        <x-chief::fragments :owner="$model" />
    @endAdminCan

    <x-chief-form::forms position="main-bottom" />

    <x-slot name="aside">
        <x-chief-form::forms position="aside-top" />
        <x-chief::window.states />
        <x-chief::window.links />
        <x-chief-form::forms position="aside" />
    </x-slot>

    @include('chief::manager._edit.state-modals-and-redactor')
</x-chief::page>
