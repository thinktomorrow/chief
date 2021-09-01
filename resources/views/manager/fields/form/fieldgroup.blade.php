
@if($fieldGroup->allowsMultiple())
    MULTIPLYYY

    // Array of values
    // Add default values (they are arrays)
    // Existing GROUP[1][FIELD]
    // convert name of fields to GROUP[n+1][FIELD]
    <input type="text" name="GROUP[1][street]" value="test street">
    <input type="text" name="GROUP[1][city]" value="test city">

    // Numbers of existing values ...
    <?php
        //$existingEntriesCount = $fieldGroup->first()-> ?>

    @foreach($fieldGroup->all() as $i => $field)
        <?php $field->placeholders('index', $i); ?>
        @include('chief::manager.fields.form.field', ['autofocus' => (isset($index) && $index === 0)])
    @endforeach
@else
    @foreach($fieldGroup->all() as $field)
        @include('chief::manager.fields.form.field', ['autofocus' => (isset($index) && $index === 0)])
    @endforeach
@endif


