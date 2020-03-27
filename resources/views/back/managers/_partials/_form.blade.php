@foreach($fields as $field)
    @formgroup
        @slot('label',$field->getLabel())
        @slot('description',$field->getDescription())
        @slot('isRequired', $field->required())
        {!! $field->render() !!}
    @endformgroup
@endforeach
