{{-- <x-chief::page> --}}
<x-chief::template :title="$resource->getPageTitle($model)">
    <x-slot name="hero">
        <x-chief::template.hero>
            @if($forms->has('pagetitle'))
                <x-slot name="customTitle">
                    <x-chief-form::forms id="pagetitle" />
                </x-slot>
            @else
                <x-slot name="title">
                    {{ $resource->getPageTitle($model) }}
                </x-slot>
            @endif

            @if(count(config('chief.locales')) > 1)
                <tabs class="-mb-3">
                    @foreach(config('chief.locales') as $locale)
                        <tab v-cloak id="{{ $locale }}" name="{{ $locale }}"></tab>
                    @endforeach
                </tabs>
            @endif
        </x-chief::template.hero>
    </x-slot>

    <div class="container">
        <div class="row-start-start gutter-3">
            <div class="w-full space-y-6 lg:w-2/3">
                <x-chief-form::forms position="main" />

                @adminCan('fragments-index', $model)
                    <x-chief::fragments :owner="$model" />
                @endAdminCan

                <x-chief-form::forms position="main-bottom" />
            </div>

            <div class="w-full space-y-6 lg:w-1/3">
                {{-- TODO: add sidebar config to template --}}
                <x-chief-form::forms position="aside-top" />
                <x-chief::window.states />
                <x-chief::window.links />
                <x-chief-form::forms position="aside" />
            </div>
        </div>
    </div>

    @include('chief::manager._edit.state-modals-and-redactor')
</x-chief::template>
{{-- </x-chief::page> --}}
