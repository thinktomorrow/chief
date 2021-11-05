<div
    data-conditional
    {{-- data-conditional-trigger-type="{{ $type ?? null }}" --}}
    {{-- data-conditional-data="{{ '' }}" --}}
    data-conditional-triggers='@json($triggers)'
    class="w-full"
>
    {!! $slot !!}
</div>
