@php
    $component->ignoreDefault();
@endphp

<tr
    class="@lg:table-row @lg:[&>*:not(:last-child)]:pr-6 @lg:space-y-0 block space-y-1 [&:not(:first-child)]:pt-3 [&:not(:last-child)]:pb-3"
>
    @if ($getLabel())
        <td class="@lg:table-cell block align-top">
            <x-chief::form.label class="mt-0.5 text-sm/5 font-normal text-grey-500 @lg:max-w-48">
                {{ ucfirst($getLabel()) }}
            </x-chief::form.label>
        </td>
    @endif

    <td class="@lg:table-cell block">
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
