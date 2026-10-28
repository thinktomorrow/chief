@php
    $loose = array_values(array_filter($tree, fn ($n) => ! $n['children']));
    $sections = array_values(array_filter($tree, fn ($n) => $n['children']));
    $eyebrow = $breadcrumbs ? end($breadcrumbs)['title'] : null;
@endphp

<article class="pt-d-main">
    @if ($isOverview)
        <p class="pt-d-eyebrow">Handleiding</p>
        <h1 class="h1 h1-dark">Hoe werkt Chief?</h1>
        <p class="pt-d-lead">Alles wat je moet weten om je website te beheren: van een pagina publiceren tot je menu opbouwen.</p>
        <div class="mt-6 flex flex-wrap gap-2">
            <x-chief::button variant="blue" :href="$guides['chief::welkom']['url']">Aan de slag</x-chief::button>
            <x-chief::button variant="grey" x-data x-on:click="$dispatch('pt-open-palette')"><x-chief::icon.search /><span>Zoeken</span></x-chief::button>
        </div>

        @foreach (array_merge([['title' => 'Aan de slag', 'children' => $loose]], $sections) as $group)
            <h2 class="pt-d-h2 pt-heading" id="{{ Str::slug($group['title']) }}">{{ $group['title'] }}<a href="#{{ Str::slug($group['title']) }}" class="pt-anchor" aria-hidden="true">#</a></h2>
            <div class="pt-d-cards">
                @foreach ($group['children'] as $card)
                    <a href="{{ $card['url'] }}" class="pt-d-card"><strong>{{ $card['title'] }}</strong><p>{{ $card['description'] }}</p><span>Lees meer →</span></a>
                @endforeach
            </div>
        @endforeach
    @else
        @if ($eyebrow)<p class="pt-d-eyebrow">{{ $eyebrow }}</p>@endif
        <h1 class="h1 h1-dark">{{ $guide['title'] }}</h1>
        @if ($guide['description'])<p class="pt-d-lead">{{ $guide['description'] }}</p>@endif

        <div class="prose prose-dark prose-spacing pt-d-prose mt-8 max-w-none">{!! $html !!}</div>

        @php $children = array_values(array_filter($guides, fn ($g) => $g['parent'] === $guide['id'])); @endphp
        @if ($children)
            <h2 class="pt-d-h2">In dit onderdeel</h2>
            <div class="pt-d-cards">
                @foreach ($children as $card)
                    <a href="{{ $card['url'] }}" class="pt-d-card"><strong>{{ $card['title'] }}</strong><p>{{ $card['description'] }}</p><span>Lees meer →</span></a>
                @endforeach
            </div>
        @endif

        @if ($prev || $next)
            <nav class="pt-d-pager">
                <div>@if ($prev)<a href="{{ $prev['url'] }}"><small>← Vorige</small><span>{{ $prev['title'] }}</span></a>@endif</div>
                <div class="text-right">@if ($next)<a href="{{ $next['url'] }}"><small>Volgende →</small><span>{{ $next['title'] }}</span></a>@endif</div>
            </nav>
        @endif
    @endif
</article>
