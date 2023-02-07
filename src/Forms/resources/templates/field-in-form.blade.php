@php
    $fieldType = strtolower(class_basename($component));
@endphp

<div
    {!! $getFieldToggles() ? "data-conditional-toggle='" . json_encode($getFieldToggles()) . "'" : null !!}
    data-field-key="{{ $getId($locale ?? null) }}"
    data-field-type="{{ $fieldType }}"
    {!! $fieldType == 'hidden' ? 'hidden' : null !!}
    class="space-y-1 form-light"
>
    @if ($getLabel())
        <x-chief::input.label for="..." :required="$isRequired()">
            {{ $getLabel() }}
        </x-chief::input.label>
    @endif

    @if ($getDescription())
        <x-chief::input.description>
            {{ $getDescription() }}
        </x-chief::input.description>
    @endif

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

    @if ($hasLocales())
        @foreach($getLocales() as $locale)
            <x-chief::input.error :rule="$getId($locale)"/>
        @endforeach
    @else
        <x-chief::input.error :rule="$getId()"/>
    @endif
</div>

{{-- <x-chief-form::formgroup
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
</x-chief-form::formgroup> --}}
