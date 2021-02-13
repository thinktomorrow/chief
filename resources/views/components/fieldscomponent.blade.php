<div data-fields-component="data-sidebar-fields-{{$componentKey}}-edit">
    @if(public_method_exists($model, 'render'.ucfirst($componentKey).'Component'))
        {!! $model->{'render'.ucfirst($componentKey).'Component'}() !!}
    @else
        @foreach($fields->component($componentKey) as $field)
            <!-- off course, this should be sensible, for all kinds of fieldtypes, checkboxes, images, ... ... -->
            <h3>{{ $field->getLabel() }}</h3>
            <p>{!! $field->getValue() !!}</p>
        @endforeach

        <a data-sidebar-fields-{{$componentKey}}-edit href="@adminRoute('fields-edit', $model, $componentKey)">edit these fields</a>
    @endif
</div>
