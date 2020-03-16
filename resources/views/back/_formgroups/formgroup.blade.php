<section class="row formgroup stack gutter-l">
    <div class="column-4">
        <h2>
            <label for="{{ $formgroup->getKey() }}">{{ ucfirst($formgroup->getLabel()) }}</label>
            @if(!$formgroup->isRequired()) <span class="font-xs text-grey-300">(Optioneel)</span> @else <span class="font-xs text-warning">(Verplicht)</span> @endif
        </h2>

        @if($formgroup->getDescription())
            <p>{!! $formgroup->getDescription() !!}</p>
        @endif
    </div>
    <div class="formgroup-input column-8">

        @foreach($formgroup->fields() as $field)
            @include($field->getView(), $field->getViewData())
        @endforeach

    </div>
</section>
