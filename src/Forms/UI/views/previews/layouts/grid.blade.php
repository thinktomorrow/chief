<table {{ $attributes->merge($getCustomAttributes())->class('w-full') }}>
    <tbody>
        @foreach ($getComponents() as $childComponent)
            {{ $childComponent->renderPreview() }}
        @endforeach
    </tbody>
</table>
