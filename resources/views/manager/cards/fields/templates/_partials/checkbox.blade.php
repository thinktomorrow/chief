@if($field->getValue())
    @php
        $values = gettype($field->getValue()) == 'array' ? $field->getValue() : array($field->getValue());
    @endphp

    <div class="flex flex-wrap -m-0.5">
        @foreach($field->getOptions() as $optionKey => $optionValue)
            @if(in_array($optionKey, $values))
                <div class="p-0.5">
                    <span class="inline-block label label-info">{{ $optionValue }}</span>
                </div>
            @endif
        @endforeach
    </div>
@else
    <p><span class="text-grey-400">...</span></p>
@endif
