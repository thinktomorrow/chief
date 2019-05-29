<section class="row formgroup stack gutter-l">
    <div class="column-4">
        @if($field->label)
            <h2><label for="{{ $key }}">{{ ucfirst($field->label) }}</label></h2>
        @endif

        @if($field->description)
            <p>{!! $field->description !!}</p>
        @endif
    </div>
    <div class="formgroup-input column-8">
        @if($field->isTranslatable())
            @include('chief::back._fields.translatable')
        @else
            @include('chief::back._fields.'.$field->type)
        @endif
    </div>
</section>
