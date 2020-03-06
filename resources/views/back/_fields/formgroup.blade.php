<section class="row formgroup stack gutter-l">
    <div class="column-4">
        <h2>
            <label for="{{ $key }}">{{ ucfirst($field->getLabel()) }}</label>
            @if($field->optional()) <span class="font-xs text-grey-300">(Optioneel)</span> @else <span class="font-xs text-warning">(Verplicht)</span> @endif
        </h2>

        @if($field->getDescription())
            <p>{!! $field->getDescription() !!}</p>
        @endif
    </div>
    <div class="formgroup-input column-8">
        @include($field->getElementView())
    </div>
</section>
