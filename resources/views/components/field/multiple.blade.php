@if(isset($tagged))
    @foreach(\Thinktomorrow\Chief\ManagedModels\Fields\Fields::make($model->fields())->tagged($tagged)->allFields() as $field)
        <x-chief::field :key="$field->getKey()" />
    @endforeach
@elseif(isset($notTagged))
    @foreach(\Thinktomorrow\Chief\ManagedModels\Fields\Fields::make($model->fields())->notTagged(explode(',',$notTagged))->allFields() as $field)
        <x-chief::field :key="$field->getKey()" />
    @endforeach
@else
    @foreach(\Thinktomorrow\Chief\ManagedModels\Fields\Fields::make($model->fields())->allFields() as $field)
        <x-chief::field :key="$field->getKey()" />
    @endforeach
@endif
