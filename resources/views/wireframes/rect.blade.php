<div
    class="inline-block bg-grey-200 rounded-sm {{ $attributes->get('class') }}"
    style="
        width: {{ $width ?? '100%' }};
        height: {{ $height ?? '1rem' }};
        {{ $attributes->get('style') }}
    "
>
    <div class="p-2 prose prose-dark prose-editor prose-wireframe">
        {{ $slot }}
    </div>
</div>
