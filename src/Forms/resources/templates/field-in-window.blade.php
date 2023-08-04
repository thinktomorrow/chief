@php
    $component->ignoreDefault();
@endphp

<div class="flex flex-wrap justify-between w-full gap-y-1 gap-x-3">
    @if ($getLabel())
        <div class="w-48">
            <span class="font-medium h6 body-dark">
                {{ ucfirst($getLabel()) }}
            </span>
        </div>
    @endif

    <div class="w-full max-w-full sm:w-128">
        @if (!$hasLocales())
            @include($getView())
        @elseif (count($getLocales()) == 1)
            @foreach ($getLocales() as $locale)
                @include($getView(), ['component' => $component, 'locale' => $locale])
            @endforeach
        @else
            <x-chief::tabs :listen-for-external-tab="true">
                @foreach($getLocales() as $locale)
                    <x-chief::tabs.tab tab-id='{{ $locale }}'>
                        @include($getView(), ['component' => $component, 'locale' => $locale])
                    </x-chief::tabs.tab>
                @endforeach
            </x-chief::tabs>
        @endif
    </div>
</div>
