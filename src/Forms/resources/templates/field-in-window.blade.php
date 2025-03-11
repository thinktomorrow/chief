@php
    $component->ignoreDefault();
@endphp

<tr>
    <td class="@md:table-cell table-row">
        <x-chief::form.label class="w-48">
            {{ ucfirst($getLabel()) }}
        </x-chief::form.label>
    </td>

    <td class="@md:table-cell table-row">
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
    </td>
</tr>

{{--
    <div class="flex w-full flex-wrap justify-between gap-x-3 gap-y-1">
    @if ($getLabel())
    <x-chief::form.label class="w-48">
    {{ ucfirst($getLabel()) }}
    </x-chief::form.label>
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
--}}
