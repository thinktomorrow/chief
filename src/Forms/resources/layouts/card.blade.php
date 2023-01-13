<div {{ $attributes->merge($getCustomAttributes())->class([
    'p-6 rounded-lg',
    $getLayoutType()->class(),
]) }}>
    <div class="space-y-6">
        @include('chief-form::layouts._partials.header')

        <div>
            @foreach($getComponents() as $childComponent)
                {{ $childComponent }}
            @endforeach
        </div>
    </div>
</div>
