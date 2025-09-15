@if (count($getComponents()) > 0)
    <div data-slot="form-group" {{ $attributes->merge($getCustomAttributes()) }}>
        <div class="gutter-3 flex flex-wrap items-start justify-start">
            @if ($getTitle() || $getDescription())
                <div class="w-full space-y-1">
                    @if ($getTitle())
                        <span class="body-dark text-sm font-medium tracking-wider uppercase">{{ $getTitle() }}</span>
                    @endif

                    @if ($getDescription())
                        <p class="body text-grey-500 text-sm">{!! $getDescription() !!}</p>
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
