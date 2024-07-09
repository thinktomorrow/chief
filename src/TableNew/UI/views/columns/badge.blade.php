@foreach($getValues() as $badge)
    @php
        $value = $badge->getValue();
        $type = $badge->getType($value);
    @endphp

    @if($type === 'green')
        <span class="bui-label bui-label-xs bui-label-green">{{ $value }}</span>
    @elseif($type === 'red')
        <span class="bui-label bui-label-xs bui-label-red">{{ $value }}</span>
    @elseif($type && str_starts_with($type, '#'))
        <span class="bui-label bui-label-xs" style="background-color:{{ $type }}">{{ $value }}</span>
    @else
        <span class="bui-label bui-label-xs bui-label-grey">{{ $value }}</span>
    @endif
@endforeach

