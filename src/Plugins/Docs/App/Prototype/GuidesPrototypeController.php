<?php

namespace Thinktomorrow\Chief\Plugins\Docs\App\Prototype;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use League\CommonMark\GithubFlavoredMarkdownConverter;

/**
 * PROTOTYPE (wayfinder ticket 08, "What does the guides UI look like?").
 * Throwaway: stub guide tree, no sources, no cache. Delete after the decision.
 *
 * Query params: role=admin|author, empty=1.
 */
class GuidesPrototypeController
{
    public function show(Request $request, ?string $id = null)
    {
        $variant = strtoupper($request->query('variant', 'E'));
        $role = $request->query('role', 'admin');
        $empty = $request->boolean('empty');

        $guides = $empty ? [] : $this->visibleGuides($role);
        $id = $id ?: ($guides ? 'chief::welkom' : null);

        if ($id && ! isset($guides[$id])) {
            abort(404);
        }

        $guide = $id ? $guides[$id] : null;
        $isOverview = $request->route('id') === null;

        $html = null;
        $toc = [];
        if ($guide && $guide['body']) {
            [$html, $toc] = $this->render($guide['body']);
        }

        $flat = $this->flatten($guides, null);
        $pages = array_values(array_filter($flat, fn ($g) => $g['body'] !== null));
        $index = array_search($id, array_column($pages, 'id'), true);

        return view('chief-docs::prototype.page', [
            'variant' => 'E',
            'role' => $role,
            'empty' => $empty,
            'guides' => $guides,
            'tree' => $this->tree($guides, null),
            'guide' => $guide,
            'isOverview' => $isOverview,
            'html' => $html,
            'toc' => $toc,
            'breadcrumbs' => $breadcrumbs = $guide ? $this->ancestors($guides, $guide) : [],
            'crumbs' => [
                ['label' => 'Handleiding', 'url' => url(config('chief.route.prefix', 'admin').'/docs-prototype').'?'.http_build_query($request->only('variant', 'role', 'empty'))],
                ...array_map(fn ($a) => $a['body'] !== null ? ['label' => $a['title'], 'url' => $a['url']] : $a['title'], $breadcrumbs),
            ],
            'prev' => $index !== false && $index > 0 ? $pages[$index - 1] : null,
            'next' => $index !== false && $index < count($pages) - 1 ? $pages[$index + 1] : null,
            'searchIndex' => array_values(array_map(fn ($g) => [
                'id' => $g['id'],
                'title' => $g['title'],
                'description' => $g['description'],
                'section' => $g['parent'] ? ($guides[$g['parent']]['title'] ?? null) : null,
                'text' => $this->plain($g['body'] ?? ''),
                'url' => $this->url($g['id']),
            ], array_filter($guides, fn ($g) => $g['body'] !== null))),
        ]);
    }

    public function asset(string $name)
    {
        $label = e(Str::headline(pathinfo($name, PATHINFO_FILENAME)));
        $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="1200" height="700" viewBox="0 0 1200 700">
  <rect width="1200" height="700" fill="#f4f4f5"/>
  <rect x="0" y="0" width="240" height="700" fill="#fafafa"/>
  <g fill="#d4d4d8">
    <rect x="32" y="40" width="120" height="14" rx="7"/><rect x="32" y="90" width="160" height="12" rx="6"/>
    <rect x="32" y="122" width="140" height="12" rx="6"/><rect x="32" y="154" width="150" height="12" rx="6"/>
  </g>
  <rect x="290" y="40" width="380" height="28" rx="8" fill="#a1a1aa"/>
  <rect x="290" y="100" width="860" height="540" rx="16" fill="#fff" stroke="#e4e4e7"/>
  <g fill="#e4e4e7">
    <rect x="320" y="140" width="420" height="16" rx="8"/><rect x="320" y="180" width="800" height="48" rx="10"/>
    <rect x="320" y="250" width="800" height="48" rx="10"/><rect x="320" y="320" width="800" height="48" rx="10"/>
  </g>
  <rect x="960" y="40" width="190" height="36" rx="10" fill="#3b82f6"/>
  <circle cx="1055" cy="58" r="44" fill="none" stroke="#ef4444" stroke-width="5"/>
  <text x="600" y="520" font-family="sans-serif" font-size="22" fill="#71717a" text-anchor="middle">Screenshot: {$label}</text>
</svg>
SVG;

        return response($svg, 200, ['Content-Type' => 'image/svg+xml']);
    }

    private function url(string $id): string
    {
        return url(config('chief.route.prefix', 'admin').'/docs-prototype/'.$id).'?'.http_build_query(request()->only('variant', 'role'));
    }

    private function visibleGuides(string $role): array
    {
        $guides = [];
        foreach ($this->stubs() as $id => $stub) {
            $guides[$id] = ['id' => $id, 'url' => $this->url($id)] + $stub + ['parent' => null, 'description' => null, 'body' => null, 'roles' => []];
        }

        foreach ($guides as $id => $guide) {
            if ($guide['roles'] && ! in_array($role, $guide['roles'])) {
                unset($guides[$id]);
                foreach ($guides as $childId => $child) {
                    if (str_starts_with($childId, $id.'/')) {
                        unset($guides[$childId]);
                    }
                }
            }
        }

        return $guides;
    }

    private function tree(array $guides, ?string $parent): array
    {
        return array_values(array_map(
            fn ($g) => $g + ['children' => $this->tree($guides, $g['id'])],
            array_filter($guides, fn ($g) => $g['parent'] === $parent),
        ));
    }

    private function flatten(array $guides, ?string $parent): array
    {
        $out = [];
        foreach ($guides as $g) {
            if ($g['parent'] === $parent) {
                $out[] = $g;
                array_push($out, ...$this->flatten($guides, $g['id']));
            }
        }

        return $out;
    }

    private function ancestors(array $guides, array $guide): array
    {
        $chain = [];
        while ($guide['parent'] && isset($guides[$guide['parent']])) {
            $guide = $guides[$guide['parent']];
            array_unshift($chain, $guide);
        }

        return $chain;
    }

    private function render(string $markdown): array
    {
        $html = (new GithubFlavoredMarkdownConverter(['allow_unsafe_links' => false]))->convert($markdown)->getContent();

        $html = preg_replace('#<h1>.*?</h1>#s', '', $html, 1);

        $toc = [];
        $html = preg_replace_callback('#<h([23])>(.*?)</h\1>#s', function ($m) use (&$toc) {
            $slug = Str::slug(strip_tags($m[2]));
            $toc[] = ['level' => (int) $m[1], 'label' => strip_tags($m[2]), 'slug' => $slug];

            return "<h{$m[1]} id=\"{$slug}\" class=\"pt-heading\">{$m[2]}<a href=\"#{$slug}\" class=\"pt-anchor\" aria-hidden=\"true\">#</a></h{$m[1]}>";
        }, $html);

        $labels = ['TIP' => ['Tip', 'green'], 'NOTE' => ['Goed om te weten', 'blue'], 'WARNING' => ['Let op', 'orange']];
        $html = preg_replace_callback('#<blockquote>\s*<p>\[!(TIP|NOTE|WARNING)\]\s*(.*?)</blockquote>#s', function ($m) use ($labels) {
            [$label, $color] = $labels[$m[1]];

            return "<div class=\"pt-callout pt-callout-{$color} not-prose\"><p class=\"pt-callout-title\">{$label}</p><div><p>{$m[2]}</div></div>";
        }, $html);

        $html = preg_replace_callback('#<p><img src="([^"]+)" alt="([^"]*)"\s*/?></p>#', fn ($m) => '<figure class="pt-figure"><a href="'.$m[1].'" data-pt-zoom><img src="'.$m[1].'" alt="'.$m[2].'"></a>'.($m[2] ? '<figcaption>'.$m[2].'</figcaption>' : '').'</figure>', $html);

        return [$html, $toc];
    }

    private function plain(string $markdown): string
    {
        return trim(preg_replace('/\s+/', ' ', strip_tags((new GithubFlavoredMarkdownConverter)->convert($markdown)->getContent())));
    }

    private function stubs(): array
    {
        $short = fn (string $title, string $intro) => "# {$title}\n\n{$intro}\n\n## Hoe begin ik?\n\n1. Ga in het menu links naar het juiste onderdeel.\n2. Klik op **Toevoegen** rechtsboven.\n3. Vul de velden in en klik op **Opslaan**.\n\n> [!TIP]\n> Afhankelijk van je project zie je hier andere velden.\n";

        return [
            'chief::welkom' => ['title' => 'Welkom in Chief', 'description' => 'Wat je met Chief kan en waar je alles vindt.', 'body' => <<<'MD'
# Welkom in Chief

Chief is het beheer van je website. In deze handleiding lees je hoe je pagina's maakt, media beheert en je menu's opbouwt.

## Waar vind ik wat?

| Onderdeel | Wat doe je er? |
| --- | --- |
| **Pagina's** | Pagina's aanmaken, bewerken en publiceren |
| **Mediabibliotheek** | Afbeeldingen en documenten beheren |
| **Menu's** | De navigatie van je website opbouwen |
| **Teksten** | Vaste teksten van de website aanpassen |

## Hoe gebruik ik deze handleiding?

Gebruik het zoekveld om snel een antwoord te vinden, of blader door de onderwerpen.

> [!NOTE]
> Je ziet enkel de handleidingen voor de onderdelen waar je toegang toe hebt.
MD],
            'chief::paginas' => ['title' => "Pagina's aanmaken en bewerken", 'description' => 'Een pagina toevoegen, inhoud invullen en opslaan.', 'body' => $short("Pagina's aanmaken en bewerken", 'Een pagina bestaat uit velden en fragmenten. Zo maak je er een aan.')],
            'chief::paginas/overzichten' => ['parent' => 'chief::paginas', 'title' => 'Overzichten: zoeken, filteren en herschikken', 'description' => 'Pagina\'s terugvinden in lange lijsten.', 'body' => $short('Overzichten', 'In elk overzicht kan je zoeken, filteren en de volgorde aanpassen.')],
            'chief::paginas/status' => ['parent' => 'chief::paginas', 'title' => 'Status: draft, publiceren en archiveren', 'description' => 'Wanneer een pagina zichtbaar is op de website.', 'body' => <<<'MD'
# Status: draft, publiceren en archiveren

Elke pagina heeft een status. Die bepaalt of bezoekers de pagina op de website zien.

## Welke statussen zijn er?

| Status | Zichtbaar op de website? | Wanneer gebruik je het? |
| --- | --- | --- |
| **Draft** | Nee | Je werkt nog aan de pagina |
| **Gepubliceerd** | Ja | De pagina is klaar |
| **Gearchiveerd** | Nee | De pagina is niet meer nodig, maar je wil ze bewaren |

## Hoe publiceer ik een pagina?

1. Open de pagina.
2. Klik rechtsboven op de status **Draft**.
3. Kies **Publiceren**.

![De statusknop rechtsboven op een pagina](/admin/docs-prototype-asset/status-knop.svg)

> [!TIP]
> Wil je eerst nakijken hoe de pagina eruitziet? Gebruik **Bekijk voorbeeld** voor je publiceert.

### Wat gebeurt er met de link?

Zodra je publiceert, is de pagina bereikbaar op haar link. Lees meer in [Links, URL's en redirects](#).

## Hoe archiveer ik een pagina?

1. Open de pagina.
2. Klik op de status en kies **Archiveren**.
3. Kies optioneel een pagina waarnaar bezoekers worden doorgestuurd.

> [!WARNING]
> Een gearchiveerde pagina verdwijnt van de website. Links ernaar werken niet meer, tenzij je een doorverwijzing kiest.

## Hoe haal ik een pagina uit het archief?

Ga in het overzicht naar de filter **Archief**, open de pagina en kies **Herstellen**. De pagina krijgt dan de status **Draft**.
MD],
            'chief::paginas/links' => ['parent' => 'chief::paginas', 'title' => "Links, URL's en redirects", 'description' => 'De link van een pagina wijzigen zonder bezoekers te verliezen.', 'body' => $short("Links, URL's en redirects", 'Elke pagina heeft per taal een link.')],
            'chief::paginas/fragmenten' => ['parent' => 'chief::paginas', 'title' => 'Fragmenten', 'description' => 'Blokken inhoud toevoegen, ordenen en delen.', 'body' => $short('Fragmenten', 'Fragmenten zijn blokken waarmee je een pagina opbouwt.')],
            'npzi-fragmenten' => ['parent' => 'chief::paginas/fragmenten', 'title' => 'Fragmenten op de NPZI-site', 'description' => 'Welke fragmenten deze website heeft.', 'body' => $short('Fragmenten op de NPZI-site', 'Een overzicht van de fragmenten die je op deze website kan gebruiken.')],
            'chief::paginas/sites-en-talen' => ['parent' => 'chief::paginas', 'title' => 'Sites, talen en versies', 'description' => 'Inhoud per taal of site invullen.', 'body' => $short('Sites, talen en versies', 'Afhankelijk van je project beheer je meerdere sites of talen.')],
            'admintoast::voorbeeld' => ['parent' => 'chief::paginas', 'title' => 'Voorbeeld bekijken', 'description' => 'Een pagina bekijken voor je publiceert.', 'body' => $short('Voorbeeld bekijken', 'Bekijk een pagina zoals bezoekers ze zullen zien.')],
            'chief::media' => ['title' => 'Media en de mediabibliotheek', 'description' => 'Afbeeldingen en documenten uploaden en hergebruiken.', 'body' => $short('Media en de mediabibliotheek', 'Alle bestanden staan in de mediabibliotheek.')],
            'imagecrop::bijsnijden' => ['parent' => 'chief::media', 'title' => 'Afbeeldingen bijsnijden', 'description' => 'Een uitsnede kiezen per formaat.', 'body' => $short('Afbeeldingen bijsnijden', 'Kies welk deel van een afbeelding getoond wordt.')],
            'seo::alt-teksten' => ['parent' => 'chief::media', 'title' => 'Alt-teksten', 'description' => 'Afbeeldingen beschrijven voor zoekmachines en schermlezers.', 'body' => $short('Alt-teksten', 'Een alt-tekst beschrijft wat er op een afbeelding staat.')],
            'chief::menus' => ['title' => "Menu's beheren", 'description' => 'De navigatie van je website opbouwen.', 'body' => $short("Menu's beheren", 'Menu\'s bepalen de navigatie op je website.')],
            'chief::teksten' => ['title' => 'Teksten', 'description' => 'Vaste teksten van de website aanpassen.', 'body' => $short('Teksten', 'Vaste teksten zoals knoppen en foutmeldingen pas je hier aan.')],
            'tags::tags' => ['title' => 'Tags', 'description' => 'Pagina\'s groeperen met tags.', 'body' => $short('Tags', 'Met tags groepeer je pagina\'s.')],
            'npzi-handleiding' => ['title' => 'Opleidingen beheren', 'description' => 'Opleidingen, inschrijvingen en mails voor NPZI.', 'body' => $short('Opleidingen beheren', 'Hoe je opleidingen en inschrijvingen beheert.')],
            'chief::beheer' => ['title' => 'Beheer', 'roles' => ['admin']],
            'chief::beheer/instellingen' => ['parent' => 'chief::beheer', 'title' => 'Instellingen en sitemap', 'description' => 'Algemene instellingen van de website.', 'body' => $short('Instellingen en sitemap', 'Algemene instellingen beheer je onder Instellingen.')],
            'chief::beheer/admins' => ['parent' => 'chief::beheer', 'title' => 'Admins, rollen, rechten en log', 'description' => 'Wie mag wat in Chief.', 'body' => $short('Admins, rollen, rechten en log', 'Nodig collega\'s uit en geef ze een rol.')],
        ];
    }
}
