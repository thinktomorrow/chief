@php
    $component->ignoreDefault();
@endphp

<tr class="[&>*:first-child]:pl-4 [&>*:last-child]:pr-4 [&>*:not(:last-child)]:pr-6">
    <td class="@lg:table-cell table-row align-top">
        <x-chief::form.label class="leading-6 @lg:max-w-48">
            {{ ucfirst($getLabel()) }}
        </x-chief::form.label>
    </td>

    <td class="@lg:table-cell table-row">
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
