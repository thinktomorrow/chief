@if($field->getValue())
    @php
        $values = gettype($field->getValue()) == 'array' ? $field->getValue() : array($field->getValue());
    @endphp

    <div class="flex flex-wrap -m-0.5">
        @foreach($values as $value)
            <div class="p-0.5">
                <span class="inline-block label label-info">{{ $value }}</span>
            </div>
        @endforeach
    </div>
@else
    <p><span class="text-grey-400">...</span></p>
@endif
