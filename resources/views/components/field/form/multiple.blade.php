@if(isset($key))
    <x-chief::field.form :key="$key" :toggle="$toggle ?? null" />
@else
    @php
        $fields = $fields ?? \Thinktomorrow\Chief\Forms\Fields::make($model->fields())->model(
            $model instanceof \Thinktomorrow\Chief\Fragments\Fragmentable ? $model->fragmentModel() : $model
        );

        if(isset($key)) {
            $fields = $fields->keyed(explode(',', $key));
        }

        if(isset($tagged)) {
            $fields = $fields->tagged(explode(',', $tagged));
        }

        if(isset($notTagged)) {
            $fields = $fields->notTagged(explode(',', $notTagged));
        }

    @endphp

    @foreach($fields->all() as $field)
        <x-chief::field.form :field="$field" :toggle="$toggle ?? null"/>
    @endforeach
@endif
