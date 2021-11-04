@php
    if(isset($key) || isset($field)) {
        $field = $field ?? $model->field($key);
        $label = $field->getLabel();
    }
@endphp

@isset($field)
    <div class="space-y-1">
        @isset($label)
            <span class="font-medium text-black">
                {{ ucfirst($label) }}
            </span>
        @endisset

        {!! $field->renderOnPage() !!}
    </div>
@endisset
