@php
    $selected = (array) $getActiveValue($locale ?? null);
@endphp

@if(count($selected) > 0)
    <div class="flex flex-wrap -m-0.5">
        @if(isset($isGrouped) && $isGrouped())
            @foreach($getOptions() as $fieldGroup)
                @foreach($fieldGroup['values'] as $optionValue)
                    @if(in_array($optionValue['id'], $selected))
                        <div class="p-0.5">
                            <span class="inline-block label label-sm label-info">{{ $optionValue['label'] }}</span>
                        </div>
                    @endif
                @endforeach
            @endforeach
        @else
            @foreach($getOptions() as $optionKey => $optionValue)
                @if(in_array($optionKey, $selected))
                    <div class="p-0.5">
                        <span class="inline-block label label-sm label-info">{{ $optionValue }}</span>
                    </div>
                @endif
            @endforeach
        @endif
    </div>
@else
    <p>...</p>
@endif
