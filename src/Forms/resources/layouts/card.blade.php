<div {{ $attributes->merge($getCustomAttributes())->class([
    'p-6 rounded-lg',
    $getLayoutType()->cardClass(),
]) }}>
    <div class="space-y-6">
        @include('chief-form::layouts._partials.header')

        <div class="space-y-6">
            @foreach($getComponents() as $childComponent)
                {{ $childComponent }}
            @endforeach
        </div>
    </div>
</div>
