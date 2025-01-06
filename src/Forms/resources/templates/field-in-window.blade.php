@php
    $component->ignoreDefault();
@endphp

<div class="flex w-full flex-wrap justify-between gap-x-3 gap-y-1">
    @if ($getLabel())
        <div class="w-48">
            <span class="text-sm text-grey-500">
                {{ ucfirst($getLabel()) }}
            </span>
        </div>
    @endif

    <div class="w-full max-w-full sm:w-128">
        @if (! $hasLocales())
            @include($getView())
        @elseif (count($getLocales()) == 1)
            @foreach ($getLocales() as $locale)
                @include($getView(), ['component' => $component, 'locale' => $locale])
            @endforeach
        @else
            <x-chief::tabs :show-nav="false" :listen-for-external-tab="true">
                @foreach ($getLocales() as $locale)
                    <x-chief::tabs.tab tab-id="{{ $locale }}">
                        @include($getView(), ['component' => $component, 'locale' => $locale])
                    </x-chief::tabs.tab>
                @endforeach
            </x-chief::tabs>
        @endif
    </div>
</div>
