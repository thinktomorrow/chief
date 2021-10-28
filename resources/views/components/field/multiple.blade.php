@if(isset($tagged))
    @foreach(\Thinktomorrow\Chief\ManagedModels\Fields\Fields::make($model->fields())->tagged($tagged)->all() as $field)
        <x-chief::field :key="$field->getKey()" />
    @endforeach
@elseif(isset($notTagged))
    @foreach(\Thinktomorrow\Chief\ManagedModels\Fields\Fields::make($model->fields())->notTagged(explode(',',$notTagged))->all() as $field)
        <x-chief::field :key="$field->getKey()" />
    @endforeach
@else
    @foreach(\Thinktomorrow\Chief\ManagedModels\Fields\Fields::make($model->fields())->all() as $field)
        <x-chief::field :key="$field->getKey()" />
    @endforeach
@endif
