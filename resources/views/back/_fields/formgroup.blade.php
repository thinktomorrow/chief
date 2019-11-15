<section class="row formgroup stack gutter-l">
    <div class="column-4">
        @if($field->label)
            <h2>
                <label for="{{ $key }}">{{ ucfirst($field->label) }}</label>  
                @if($field->optional()) <span class="font-xs text-grey-300">(Optioneel)</span> @else <span class="font-xs text-warning">(Verplicht)</span> @endif
            </h2>
           
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
