{{-- TODO: finish component --}}
{{-- <x-chief::input.group rule="...">

</x-chief::input.group> --}}

<x-chief-form::formgroup
    :id="$getId($locale ?? null)"
    :label="$getLabel()"
    :description="$getDescription()"
    :required="$isRequired()"
    :fieldToggles="$getFieldToggles()"
    :fieldType="strtolower(class_basename($component))"
>
    @if(!$hasLocales())
        @include($getView())
        @include('chief-form::fields._partials.charactercount')
    @elseif(count($getLocales()) == 1)
        @foreach($getLocales() as $locale)
            @include($getView(), ['component' => $component, 'locale' => $locale])
            @include('chief-form::fields._partials.charactercount')
        @endforeach
    @else
        <div data-vue-fields>
            <tabs>
                @foreach($getLocales() as $locale)
                    <tab v-cloak id="{{ $locale }}" name="{{ $locale }}">
                        @include($getView(), ['component' => $component, 'locale' => $locale])
                        @include('chief-form::fields._partials.charactercount')
                    </tab>
                @endforeach
            </tabs>
        </div>
    @endif
    @include('chief-form::components.formgroup.error')
</x-chief-form::formgroup>
