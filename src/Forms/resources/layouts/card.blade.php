<div {{ $attributes->merge($getCustomAttributes())->class([
    'p-6 rounded-lg',
    $getLayoutType()->cardClass(),
]) }}>
    <div class="space-y-6">
        @include('chief-form::layouts._partials.header')

        @if(count($components= $getComponents()) > 0)
            <div class="space-y-6">
                @foreach($components as $childComponent)
                    {{ $childComponent }}
                @endforeach
            </div>
        @endif
    </div>
</div>
