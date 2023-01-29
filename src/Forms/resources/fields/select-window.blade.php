@php
    $selected = (array) $getActiveValue($locale ?? null);
@endphp

@if(count($selected) > 0)
    <div class="flex flex-wrap gap-0.5">
        @if(isset($isGrouped) && $isGrouped())
            @foreach($getOptions() as $fieldGroup)
                @foreach($fieldGroup['values'] as $optionValue)
                    @if(in_array($optionValue['id'], $selected))
                        <span class="inline-block label label-sm label-grey">{{ $optionValue['label'] }}</span>
                    @endif
                @endforeach
            @endforeach
        @else
            @foreach($selected as $selectedValue)
                @if(isset($getOptions()[$selectedValue]))
                    <span class="inline-block label label-sm label-grey">{{ $getOptions()[$selectedValue] }}</span>
                @endif
            @endforeach
        @endif
    </div>
@else
    <p class="body body-dark">...</p>
@endif
