@if ($slot->isNotEmpty())
    <p {{ $attributes->merge(['data-slot' => 'description'])->class(['body text-sm text-grey-500']) }}>
        {{ $slot }}
    </p>
@endif
