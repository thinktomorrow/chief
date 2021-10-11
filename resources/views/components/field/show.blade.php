@if(isset($tagged))
    @foreach(\Thinktomorrow\Chief\ManagedModels\Fields\Fields::make($model->fields())->tagged($tagged)->allFields() as $field)
        <x-chief::field.show :key="$field->getKey()" />
    @endforeach
@else
    {!! $model->field($key)->renderRead() !!}
@endif

