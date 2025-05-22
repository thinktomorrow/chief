@if (count($getComponents()) > 0)
    <div data-slot="form-group" {{ $attributes->merge($getCustomAttributes()) }}>
        <div class="gutter-3 flex flex-wrap items-start justify-start">
            @include('chief-form::layouts._partials.header')

            @foreach ($getComponents() as $childComponent)
                <div class="{{ $getColumnSpanStyle($getSpan($loop->index)) }}">
                    {{ $childComponent }}
                </div>
            @endforeach
        </div>
    </div>
@endif
