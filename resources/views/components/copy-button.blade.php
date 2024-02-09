@props([
    'content' => null,
    'successContent' => 'Gekopiëerd!',
])

<button
    {{ $attributes }}
    type="button"
    x-cloak
    x-data
    x-on:click="() => {
        navigator.clipboard.writeText('{{ $content ?? $slot }}');

        window.dispatchEvent(
            new CustomEvent('create-notification', {
                detail: {
                    type: 'success',
                    content: '{{ $successContent }}',
                    duration: 5000,
                },
            })
        );
    }"
>
    {{ $slot }}
</button>
