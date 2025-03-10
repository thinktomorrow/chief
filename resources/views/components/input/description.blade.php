<div {{ $attributes->class('prose-format prose-editor prose-spacing !mb-3 text-sm') }}>
    <p>{{ $slot }}</p>
</div>

@if ($slot->isNotEmpty())
    <p {{ $attributes->merge(['data-slot' => 'description'])->class(['body text-grey-500']) }}>
        {{ $slot }}
    </p>
@endif
