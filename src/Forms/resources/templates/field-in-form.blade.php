@php
    use Thinktomorrow\Chief\Forms\Livewire\LivewireFieldName;$fieldType = strtolower(class_basename($component));
@endphp

<div
        {!! $getFieldToggles() ? "data-conditional-toggle='" . json_encode($getFieldToggles()) . "'" : null !!}
        data-field-key="{{ $getId($locale ?? null) }}"
        data-field-type="{{ $fieldType }}"
        {!! $fieldType == 'hidden' ? 'hidden' : null !!}
        class="space-y-1 form-light"
>
    @if ($getLabel())
        <x-chief::input.label :required="$isRequired()">
            {{ $getLabel() }}
        </x-chief::input.label>
    @endif

    @if ($getDescription())
        <x-chief::input.description>
            {!! $getDescription() !!}
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
        <x-chief::tabs :listen-for-external-tab="true">
            @foreach($getLocales() as $locale)
                <x-chief::tabs.tab tab-id='{{ $locale }}'>
                    @include($getView(), ['component' => $component, 'locale' => $locale])
                    @include('chief-form::fields._partials.charactercount')
                </x-chief::tabs.tab>
            @endforeach
        </x-chief::tabs>
    @endif

    @if ($hasLocales())
        @foreach($getLocales() as $locale)
            <x-chief::input.error
                    :rule="LivewireFieldName::get($getId($locale ?? null))"/>
            <x-chief::input.error :rule="$getId($locale)"/>
        @endforeach
    @else
        <x-chief::input.error :rule="LivewireFieldName::get($getId())"/>
        <x-chief::input.error :rule="$getId()"/>
    @endif
</div>
