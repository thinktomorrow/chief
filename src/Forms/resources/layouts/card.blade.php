<div {{ $attributes->merge($getCustomAttributes())->class([
    '-mx-6 p-6',
    $getLayoutType()->class(),
]) }}>
    <div class="space-y-6">
        @include('chief-form::layouts._partials.header')

        @foreach($getComponents() as $childComponent)
            {{ $childComponent }}
        @endforeach
    </div>
</div>
