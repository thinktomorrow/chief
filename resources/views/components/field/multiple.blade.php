@if(isset($tagged))
    @foreach(\Thinktomorrow\Chief\ManagedModels\Fields\Fields::make($model->fields())->tagged($tagged)->allFields() as $field)
        <x-chief::field :key="$field->getKey()" />
    @endforeach
@endif
