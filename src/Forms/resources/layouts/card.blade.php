<div {{ $attributes->merge($getCustomAttributes())->class([
    'p-6 rounded-lg',
    $getLayoutType()->class(),
]) }}>
    <div class="space-y-6">
        @include('chief-forms::layouts._partials.header')

        @foreach($getComponents() as $childComponent)
            {{ $childComponent }}
        @endforeach
    </div>
</div>
