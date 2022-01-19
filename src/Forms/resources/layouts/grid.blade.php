<div {{ $attributes->merge($getCustomAttributes())->class('row-start-start gutter-3') }}>
    @include('chief-forms::layouts._partials.title')
    @foreach($getComponents() as $childComponent)
        <div
             @class([
                'w-full',
                'sm:w-1/2' => $getColumns() == 2,
                'sm:w-1/3' => $getColumns() == 3,
             ])
        >{{ $childComponent }}</div>
    @endforeach
</div>
