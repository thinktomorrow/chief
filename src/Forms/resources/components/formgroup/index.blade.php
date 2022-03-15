<x-chief-form::formgroup.wrapper
        :id="$getId($locale ?? null)"
        :label="$getLabel()"
        :description="$getDescription()"
        :required="$isRequired()"
        :fieldToggles="$getFieldToggles()"
        :fieldType="strtolower(class_basename($component))"
>
    @if(!$hasLocales())
        @include($getView())
        @include('chief-form::components.formgroup.error')
        @include('chief-form::fields._partials.charactercount')
    @elseif(count($getLocales()) == 1)
        @foreach($getLocales() as $locale)
            @include($getView(), ['component' => $component, 'locale' => $locale])
            @include('chief-form::fields._partials.charactercount')
            @include('chief-form::components.formgroup.error')
        @endforeach
    @else
        <div data-vue-fields>
            <tabs>
                @foreach($getLocales() as $locale)
                    <tab v-cloak id="{{ $locale }}-translatable-fields" name="{{ $locale }}">
                        @include($getView(), ['component' => $component, 'locale' => $locale])
                        @include('chief-form::fields._partials.charactercount')
                        @include('chief-form::components.formgroup.error')
                    </tab>
                @endforeach
            </tabs>
        </div>
    @endif
</x-chief-form::formgroup.wrapper>
