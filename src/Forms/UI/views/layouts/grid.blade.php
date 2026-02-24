@if (count($getComponents()) > 0)
    <div data-slot="form-group" {{ $attributes->merge($getCustomAttributes()) }}>
        <div class="gutter-3 flex flex-wrap items-start justify-start">
            @if ($getTitle() || $getDescription())
                <div class="w-full space-y-1.5">
                    @if ($getTitle())
                        <p class="font-display text-grey-900 text-lg/6 font-medium">{{ $getTitle() }}</p>
                    @endif

                    @if ($getDescription())
                        <p class="text-grey-500 text-base/6">{!! $getDescription() !!}</p>
                    @endif
                </div>
            @endif

            @foreach ($getComponents() as $_component)
                <div class="{{ $getColumnSpanStyle($getSpan($loop->index)) }}">
                    {{ $_component }}
                </div>
            @endforeach
        </div>
    </div>
@endif
