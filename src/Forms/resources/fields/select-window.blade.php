@php
    $selected = (array) $getActiveValue($locale ?? null);
@endphp

@if(count($selected) > 0)
    <div class="flex flex-wrap gap-0.5">
        {{-- check for existence of option groups (used by select field) because this view is also used by radio/checkbox fields --}}
        @if(isset($hasOptionGroups) && $hasOptionGroups($locale ?? null))
            @foreach($selected as $value)
                @foreach($getOptions() as $group)
                    @foreach($group['options'] as $optionValue)
                        @if($optionValue['value'] == $value)
                            <span class="inline-block label label-sm label-grey">{{ $optionValue['label'] }}</span>
                        @endif
                    @endforeach
                @endforeach
            @endforeach
        @else
            @foreach($selected as $value)
                @foreach($getOptions() as $optionValue)
                    @if($optionValue['value'] == $value)
                        <span class="inline-block label label-sm label-grey">{{ $optionValue['label'] }}</span>
                    @endif
                @endforeach
            @endforeach
        @endif
    </div>
@else
    <p class="body body-dark">...</p>
@endif
