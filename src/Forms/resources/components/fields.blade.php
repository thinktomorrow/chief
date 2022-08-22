@php
    $fields = $forms->getFields();

    if(isset($id)) {
        $fields = $fields->keyed(explode(',', $id));
    }

    if(isset($tagged)) {
        $fields = $fields->tagged(explode(',', $tagged));
    }

    if(isset($notTagged)) {
        $fields = $fields->notTagged(explode(',', $notTagged));
    }
@endphp

@foreach($fields->all() as $field)
    {{ $field->render() }}
@endforeach
