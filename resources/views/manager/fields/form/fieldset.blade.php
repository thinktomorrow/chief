<div data-vue-fields class="space-y-8">
    @if($fieldSet->getTitle() || $fieldSet->getDescription())
        <div class="space-y-1">
            @if($fieldSet->getTitle())
                <h3 class="text-xl font-semibold text-grey-900">{{ ucfirst($fieldSet->getTitle()) }}</h4>
            @endif

            @if($fieldSet->getDescription())
                <div class="prose prose-dark prose-editor">
                    <p>{!! $fieldSet->getDescription() !!}</p>
                </div>
            @endif
        </div>
    @endif

    <div>
        <div class="row-start-start gutter-3">
            @foreach($fieldSet->all() as $field)
                @include('chief::manager.fields.form.field', ['autofocus' => (isset($index) && $index === 0)])
            @endforeach
        </div>
    </div>
</div>

{{-- @if($fieldSet->allowsMultiple())
    MULTIPLYYY

    // Array of values
     // Add default values (they are arrays)
     // Existing GROUP[1][FIELD]
     // convert name of fields to GROUP[n+1][FIELD]
     <input type="text" name="GROUP[1][street]" value="test street">
     <input type="text" name="GROUP[1][city]" value="test city">

     // Numbers of existing values ...
     <?php
         //$existingEntriesCount = $fieldSet->first()-> ?>

    @foreach($fieldSet->all() as $i => $field)
        <?php $field->placeholders('index', $i); ?>
        @include('chief::manager.fields.form.field', ['autofocus' => (isset($index) && $index === 0)])
    @endforeach
@else
@endif --}}
