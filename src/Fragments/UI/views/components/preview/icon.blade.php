@if($slot->isNotEmpty())
    <p {{ $attributes->class('[&>*]:w-12 [&>*]:h-12 [&>*]:body-dark') }}>
        {{ $slot }}
    </p>
@endif
