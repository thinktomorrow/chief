<div>
    <livewire:chief-wire::repeat
        :model="$field->getModel()"
        :form-id="$this->form->getId()"
        :field-key="$field->getKey()"
        prefix="{{ $prefix ?? null }}"
        wire:key="{{ $this->form->getId().'-'.$field->getId() }}"
    />
</div>


{{--@php--}}
{{--    $repeatedComponents = $getRepeatedComponents($locale ?? null);--}}
{{--@endphp--}}

{{--<div--}}
{{--    data-repeat--}}
{{--    data-repeat-endpoint="{{ $getEndpoint() }}"--}}
{{--    data-repeat-section-name="{{ $getName() }}"--}}
{{--    id="{{ $getElementId($locale ?? null) }}"--}}
{{--    class="space-y-3"--}}
{{-->--}}
{{--    @foreach($repeatedComponents as $components)--}}
{{--        @include($getSectionView())--}}
{{--    @endforeach--}}

{{--    <button type="button" data-add-repeat-section class="w-full btn btn-grey">--}}
{{--        <span class="inline-block w-full text-center">--}}
{{--            Voeg een extra blok toe--}}
{{--        </span>--}}
{{--    </button>--}}
{{--</div>--}}
