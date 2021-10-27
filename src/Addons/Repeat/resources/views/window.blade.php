@foreach($field->getRepeatedFields()->all() as $fieldSet)
    <div class="p-3 border rounded-lg border-grey-100 space-y-3">
        @foreach($fieldSet->all() as $field)
            <div class="{{ $field->getWidthStyle() }} space-y-2">
                @if($field->getLabel())
                    <span class="text-xs font-semibold uppercase text-grey-700">
                        {{ ucfirst($field->getLabel()) }}
                    </span>
                @endif

                {!! $field->renderOnPage() !!}
            </div>
        @endforeach
    </div>
@endforeach

