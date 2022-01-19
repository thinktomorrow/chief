<div {{ $attributes->merge($getCustomAttributes())->class([
        '-mx-3 p-3 rounded-lg bg-white',
        $getLayoutType()->class(),
    ]) }}>
    <div class="space-y-6">
        @include('chief-forms::layouts._partials.title')
        <div class="space-y-6">
            @foreach($getComponents() as $childComponent)
                {{ $childComponent }}
            @endforeach
        </div>
    </div>
</div>
