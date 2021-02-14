<div data-fields-component="data-sidebar-fields-{{$componentKey}}-edit">
    @if(public_method_exists($model, 'render'.ucfirst($componentKey).'Component'))
        {!! $model->{'render'.ucfirst($componentKey).'Component'}() !!}
    @else
        @foreach($fields as $field)
            <!-- off course, this should be sensible, for all kinds of fieldtypes, checkboxes, images, ... ... -->
            <!-- as well as localisation ... -->
            <h3>{{ $field->getLabel() }}</h3>

            @if($field instanceof \Thinktomorrow\Chief\ManagedModels\Fields\Types\MediaField)

            @else
                <p>{!! $field->getValue() !!}</p>
            @endif
        @endforeach

        <a data-sidebar-fields-{{$componentKey}}-edit href="@adminRoute('fields-edit', $model, $componentKey)">edit these fields</a>
    @endif
</div>
