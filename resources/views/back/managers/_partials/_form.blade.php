@foreach($manager->fields() as $field)
    {!! $manager->renderField($field) !!}
@endforeach

