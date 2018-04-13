<nav class="header-nav bc-white">
    <ul class="nav-items inline-group">

        @foreach($items as $item)

            <?php

                if(!is_array($item)) $item = ['label' => $item, 'url' => $item, 'active' => false];

                $label = isset($item['label']) ? $item['label'] : null;
                $url = isset($item['url']) ? $item['url'] : $label;
                $active = false;

                if(isset($item['active']))
                {
                    $active = $item['active'];
                }
                else{
                    $parsed = parse_url($url);
                    $path = $parsed['path'] ?? '';
                    $active = isActiveUrl($path);
                }
            ?>

            <li><a class="nav-item text-primary {{ $active ? 'active':'' }}" href="{{ $url }}">{{ $label }}</a></li>
        @endforeach
    </ul>
</nav>