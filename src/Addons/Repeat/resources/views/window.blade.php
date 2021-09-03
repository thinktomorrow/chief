@foreach($field->getRepeatedFields()->all() as $fieldSet)
    @foreach($fieldSet->all() as $field)
        {!! $field->renderWindow() !!}
    @endforeach
@endforeach
