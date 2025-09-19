@if (count($getComponents()) > 0)
    <div {{ $attributes->merge(['data-slot' => 'form-group']) }}>
        <div class="gutter-3 flex flex-wrap items-start justify-start">
            @foreach ($getComponents() as $_component)
                <div class="{{ $getColumnSpanStyle($getSpan($loop->index)) }}">
                    {{ $_component }}
                </div>
            @endforeach
        </div>
    </div>
@endif
