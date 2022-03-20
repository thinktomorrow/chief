<x-chief-form::formgroup.window :label="$getLabel()">
    @if(!$hasLocales())
        @include($getView())
    @elseif(count($getLocales()) == 1)
        @foreach($getLocales() as $locale)
            @include($getView(), ['component' => $component, 'locale' => $locale])
        @endforeach
    @else
        <div data-vue-fields>
            <tabs :hide_nav="true">
                @foreach($getLocales() as $locale)
                    <tab v-cloak id="{{ $locale }}" name="{{ $locale }}">
                        @include($getView(), ['component' => $component, 'locale' => $locale])
                    </tab>
                @endforeach
            </tabs>
        </div>
    @endif
</x-chief-form::formgroup.window>
