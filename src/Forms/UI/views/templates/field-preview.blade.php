@php
    $component->ignoreDefault();
@endphp

<x-chief::form.preview :label="ucfirst($getLabel())">
    @if ($hasLocales() && count($getLocales()) == 1)
        @include($getPreviewView(), ['component' => $component, 'locale' => $getLocales()[0]])
    @elseif ($hasLocales() && count($getLocales()) > 1)
        @dd($getName(), $getLocales())
        <x-chief::tabs :show-nav="false" :should-listen-for-external-tab="true">
            @foreach ($getLocales() as $locale)
                <x-chief::tabs.tab tab-id="{{ $locale }}">
                    @include($getPreviewView(), ['component' => $component, 'locale' => $locale])
                </x-chief::tabs.tab>
            @endforeach
        </x-chief::tabs>
    @else
        @include($getPreviewView())
    @endif
</x-chief::form.preview>
