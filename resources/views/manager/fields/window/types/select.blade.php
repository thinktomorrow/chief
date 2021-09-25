@php
    $selected = (array) $field->getSelected() ?? $field->getValue($locale ?? null);
@endphp

@if(count($selected) > 0)
    <div class="flex flex-wrap -m-0.5">
        @if($field->isGrouped())
            @foreach($field->getOptions() as $fieldGroup)
                @foreach($fieldGroup['values'] as $optionValue)
                    @if(in_array($optionValue['id'], $selected))
                        <div class="p-0.5">
                            <span class="inline-block label label-info">{{ $optionValue['label'] }}</span>
                        </div>
                    @endif
                @endforeach
            @endforeach
        @else
            @foreach($field->getOptions() as $optionKey => $optionValue)
                @if(in_array($optionKey, $selected))
                    <div class="p-0.5">
                        <span class="inline-block label label-info">{{ $optionValue }}</span>
                    </div>
                @endif
            @endforeach
        @endif
    </div>
@else
    <p><span class="text-grey-400">...</span></p>
@endif
