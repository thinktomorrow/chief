<header class="bg-grey-50">
    <div class="container">
        <div class="flex justify-between row stack">
            <div class="column-9">
                <h1 class="flex items-center">
                    <span>{!! $subtitle ?? '' !!}</span>
                    <span>{!! ucfirst($title ?? '') !!}</span>
                </h1>
                {{ $extra ??  '' }}
            </div>

            <div class="justify-end text-right column-3 center-y">
                {{ $slot }}
            </div>
        </div>
    </div>
</header>
