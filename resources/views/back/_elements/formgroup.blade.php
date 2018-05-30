<?php

    // Normally field is a single string value representing the name of the input.
    // For multiple fields in the same formgroup we allow to add an array of fields as well.
    $fields = (array) $field;

    $hasErrors = false;
    foreach($fields as $field){
        if($errors->has($field)) $hasErrors = true;
    }

?>

<section class="row formgroup stack gutter-l">
    <div class="column-4">
        @if(isset($label))
            <h2 class="formgroup-label">{{ $label }}</h2>
        @endif
        @if(isset($description))
            <p class="caption">{!! $description !!}</p>
        @endif
    </div>

    <div class="input-group column-8 {{ $hasErrors ? 'error' : '' }}">
        {{ $slot }}
        @foreach($fields as $field)
            @if($errors->has($field))
                <span class="caption">{{ $errors->first($field) }}</span>
            @endif
        @endforeach
    </div>
</section>