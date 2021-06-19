<div
    {{ isset($name) ? 'data-wireframe-name=' . $name : null }}
    class="p-4 border rounded-xl border-grey-100 bg-grey-50 {{ $attributes->get('class') }}"
    style="{{ $attributes->get('style') }}"
>
    {{ $slot }}
</div>

@if(isset($css) && isset($name))
    @php
        if (! function_exists('prefixCSS')) {
            function prefixCSS($prefix, $css) {
                $parts = explode('}', $css);

                foreach ($parts as &$part) {
                    if (empty($part)) { continue; }

                    $firstPart = substr($part, 0, strpos($part, '{') + 1);
                    $lastPart = substr($part, strpos($part, '{') + 2);
                    $subParts = explode(',', $firstPart);

                    foreach ($subParts as &$subPart) {
                        $subPart = str_replace("\n", '', $subPart);
                        $subPart = $prefix . ' ' . trim($subPart);
                    }

                    $part = implode(', ', $subParts) . $lastPart;
                }

                $prefixedCSS = implode("}\n", $parts);

                return $prefixedCSS;
            }
        }

        $styleString = prefixCSS('[data-wireframe-name="' . $name . '"]', trim($css));
    @endphp

    <script type="application/javascript">
        ;(function(){
            function createWireframeStylesElement() {
                var styles = `{!! $styleString !!}`;
                var styleElement = document.createElement('style');

                styleElement.type = 'text/css';
                styleElement.setAttribute('data-wireframe-styles', '{!! $name !!}')

                if (styleElement.styleSheet) {
                    styleElement.styleSheet.cssText = styles;
                } else {
                    styleElement.appendChild(document.createTextNode(styles));
                }

                document.getElementsByTagName('head')[0].appendChild(styleElement);
            }

            if(!document.querySelector('[data-wireframe-styles="{!! $name !!}"]')) {
                createWireframeStylesElement();
            }
        })();
    </script>
@endif
