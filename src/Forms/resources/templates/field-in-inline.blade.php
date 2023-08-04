@if(!$hasLocales())
    @include($getView())
@elseif(count($getLocales()) == 1)
    @foreach($getLocales() as $locale)
        @include($getView(), ['component' => $component, 'locale' => $locale])
    @endforeach
@else
    <x-chief::tabs :show-nav="false" :listen-for-external-tab="true">
        @foreach($getLocales() as $locale)
            <x-chief::tabs.tab tab-id='{{ $locale }}'>
                @include($getView(), ['component' => $component, 'locale' => $locale])
            </x-chief::tabs.tab>
        @endforeach
    </x-chief::tabs>
@endif
