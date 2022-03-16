<div {{ $attributes->merge($getCustomAttributes())->class('row-start-end gutter-3') }}>
    @include('chief-form::layouts._partials.header')

    @foreach($getComponents() as $childComponent)
        <div class="{{ $getColumnSpanStyle($getSpan($loop->index)) }}">
            {{ $childComponent }}
        </div>
    @endforeach
</div>
