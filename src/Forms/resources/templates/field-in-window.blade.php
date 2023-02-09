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

    <div class="w-full sm:w-128">
        @if (!$hasLocales())
            @include($getView())
        @elseif (count($getLocales()) == 1)
            @foreach ($getLocales() as $locale)
                @include($getView(), ['component' => $component, 'locale' => $locale])
            @endforeach
        @else
            <div data-vue-fields>
                <tabs :hide_nav="true">
                    @foreach ($getLocales() as $locale)
                        <tab v-cloak id="{{ $locale }}" name="{{ $locale }}">
                            @include($getView(), ['component' => $component, 'locale' => $locale])
                        </tab>
                    @endforeach
                </tabs>
            </div>
        @endif
    </div>
</div>
