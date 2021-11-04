@if(isset($key))
    <x-chief::field.form :key="$key" />
@else
    @php
        $fields = $fields ?? \Thinktomorrow\Chief\ManagedModels\Fields\Fields::make($model->fields());

        if(isset($key)) {
            $fields = $fields->keyed(explode(',', $key));
        }

        if(isset($notTagged)) {
            $fields = $fields->notTagged(explode(',', $notTagged));
        }
    @endphp

    @foreach($fields->all() as $field)
        <x-chief::field.form :field="$field" />
    @endforeach
@endif
