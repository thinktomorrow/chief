@php
    $fields = $forms->getFields();

    if (isset($id)) {
        $fields = $fields->keyed(explode(',', $id));
    }

    if (isset($tagged)) {
        $fields = $fields->filterByTagged(explode(',', $tagged));
    }

    if (isset($notTagged)) {
        $fields = $fields->filterByNotTagged(explode(',', $notTagged));
    }
@endphp

@foreach ($fields->all() as $field)
    {{ $field->render() }}
@endforeach
