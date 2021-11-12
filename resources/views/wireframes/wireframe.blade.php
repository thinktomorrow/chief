<div
    @isset($name, $css)
        data-wireframe="{{ $name }}"
        data-wireframe-css="{{ $css }}"
    @endif
    class="p-4 rounded-xl bg-grey-50 bg-gradient-to-br from-grey-50 to-grey-100 {{ $attributes->get('class') }}"
    style="{{ $attributes->get('style') }}"
>
    {{ $slot }}
</div>
