<div {{ $attributes->merge($getCustomAttributes())->class('row-start-start gutter-3') }}>
    @include('chief-form::layouts._partials.header')

    @foreach($getComponents() as $childComponent)
        <div class="{{ $getColumnSpanStyle($getSpan($loop->index)) }}">
            {{ $childComponent }}
        </div>
    @endforeach
</div>
