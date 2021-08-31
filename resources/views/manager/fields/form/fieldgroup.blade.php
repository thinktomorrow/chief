@foreach($fieldGroup->all() as $field)
    @include('chief::manager.fields.form.field', ['autofocus' => (isset($index) && $index === 0)])
@endforeach
