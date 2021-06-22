<div
    @isset($name, $css)
        data-wireframe="{{ $name }}"
        data-wireframe-css="{{ $css }}"
    @endif
    class="p-4 rounded-xl border border-grey-200 bg-grey-50 {{ $attributes->get('class') }}"
    style="{{ $attributes->get('style') }}"
>
    {{ $slot }}
</div>
