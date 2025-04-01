@php
    $component->ignoreDefault();
@endphp

<x-chief::form.preview :label="ucfirst($getLabel())">
    @if (! $hasLocales())
        @include($getPreviewView())
    @elseif (count($getLocales()) == 1)
        @foreach ($getLocales() as $locale)
            @include($getPreviewView(), ['component' => $component, 'locale' => $locale])
        @endforeach
    @else
        <x-chief::tabs :show-nav="false" :should-listen-for-external-tab="true">
            @foreach ($getLocales() as $locale)
                <x-chief::tabs.tab tab-id="{{ $locale }}">
                    @include($getPreviewView(), ['component' => $component, 'locale' => $locale])
                </x-chief::tabs.tab>
            @endforeach
        </x-chief::tabs>
    @endif
</x-chief::form.preview>
