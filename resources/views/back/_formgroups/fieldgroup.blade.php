<div class="border border-grey-200 p-3">
    <label for="{{ $key }}">{{ ucfirst($field->getLabel()) }}</label>
    @if($field->optional()) <span class="font-xs text-grey-300">(Optioneel)</span> @else <span class="font-xs text-warning">(Verplicht)</span> @endif

    @if($field->getDescription())
        <p>{!! $field->getDescription() !!}</p>
    @endif

    @include($field->getElementView())
</div>

