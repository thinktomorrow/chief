# Docs

A Chief plugin to add user docs to your Chief admin.

## Install

Add the DocsServiceProvider to your list of providers.

A "Handleiding" item appears in the Chief navigation, above Instellingen, as soon as the current admin may see at least one guide. There is nothing to configure.

## Usage

Add guides as Markdown files to the project's `resources/docs` folder. Each guide is available in the Chief admin at `/admin/docs/{id}`, where the ID is its path without `.md` (`opleidingen/inschrijvingen`). Chief's own guides use the `chief::` prefix (`chief::paginas/status`). The `resources/docs/vendor` folder is reserved and not read as project guides.

A folder is a section. Its `index.md` is the section's page, served at the folder path. A guide can set these front matter keys:

```yaml
---
title: Inschrijvingen beheren   # defaults to the first H1, then the filename
description: Inschrijvingen opvolgen.
order: 1                        # siblings sort by order, then by title
parent: opleidingen             # the absolute ID of another guide or section
roles: [admin, author]          # admin roles that may see the guide, any-of
---
```

Without `roles`, or with an empty list, every admin sees the guide. A restriction on a section covers its whole subtree, and a guide beneath it can only narrow it further. A single role can be written as text (`roles: admin`). An unknown or malformed role matches nobody, and `developer` is an ordinary role without a bypass. A guide an admin may not see returns a 404.

Resolve `GuideTree` from the container to get the guides the current admin may see. Every docs surface reads from it instead of filtering on its own.

`/admin/docs` is an overview with a card per guide, grouped like the sidebar. A guide with child guides ends with cards for them, and every guide ends with links to the previous and next guide in the sidebar's order, skipping sections without a page. Without any visible guide, the overview shows an empty state.

### Search

The search bar in the sidebar, the overview's "Zoeken" button and ⌘K / Ctrl+K open a search dialog. It searches the title, description, headings and body text of the guides the admin may see, on every request, without an index.

- Every word of the query must appear somewhere in the guide, as a case- and diacritic-insensitive substring: "pagina" finds "Pagina's" and "publ" finds "publiceren". There is no stemming, and queries under 2 characters return nothing.
- Guides whose title contains every word come first, then the others, each in the sidebar's order. At most 8 results are shown.
- A result shows a snippet around the first match in the body with the matched words marked, or the description when only the title or description matched. It links to the heading with the most matched words, if any.

A Chief plugin adds its own namespaced guides from its service provider with `loadDocsFrom()`:

```php
$this->loadDocsFrom(__DIR__.'/resources/docs', 'acme');
```

Chief's plugins ship their guides this way under the `admin-toast`, `tags`, `time-table`, `hot-spots`, `seo` and `external-files` namespaces. A plugin guide can attach under a Chief section with `parent` (`parent: chief::media`).

### Overriding and hiding shipped guides

The project replaces a guide shipped by Chief or a plugin with a file at `resources/docs/vendor/<namespace>/<path>.md`. The file `resources/docs/vendor/chief/paginas/status.md` replaces `chief::paginas/status`. A section page is overridden at its ID like any guide: `vendor/chief/paginas.md` or `vendor/chief/paginas/index.md` replaces `chief::paginas`. A section folder without a page of its own has nothing to override. The override replaces the guide entirely, front matter and body, so it sets its own title, description, order, parent and roles. Nothing is merged with the original. An override without a matching shipped guide is ignored. Only the project can override; a plugin adds its own guides and attaches them with `parent`.

The project removes shipped or project guides by listing their IDs in the `hidden` key of the `chief-docs` config. Publish the config with `php artisan vendor:publish --tag=chief-docs-config`. An entry that is not a guide ID throws an `InvalidArgumentException`.

```php
// config/chief-docs.php
return [
    'hidden' => ['chief::menus', 'seo::alt-teksten'],
];
```

A hidden guide and every guide beneath it, including guides attached through `parent`, disappear from every docs surface and return a 404. Hiding is applied by the same visible tree as `roles`.

A package or project plugin whose provider does not extend `ChiefPluginServiceProvider` adds its guides from any service provider:

```php
app(\Thinktomorrow\Chief\Plugins\Docs\DocSources::class)->add('acme', __DIR__.'/../resources/docs');
```

### Links between guides

Link to another guide the way you would in an IDE or on GitHub:

```markdown
[Inschrijvingen](inschrijvingen.md)          <!-- relative to this file, within the same source -->
[Status](chief::paginas/status#publiceren)   <!-- a guide ID of any source -->
```

- A target ending in `.md` is a file relative to the current guide's file in the same source. A link in an override resolves against the location of the guide it replaces, so an override can keep the original's links.
- A target containing `::` is a namespaced guide ID.
- `#…` anchors are kept. Any other link, such as a full URL or `mailto:`, is left as written.
- A link to a guide that doesn't exist, or that the admin can't see because it is hidden or restricted by `roles`, renders as plain text.

### Images and video

Screenshots and short videos live next to the guides, anywhere inside the source folder, and are referenced by a path relative to the guide, like a `.md` link:

```markdown
![De statusknop](status-knop.png)
![Een pagina publiceren](schermen/publiceren.mp4)
```

- Allowed types are png, jpg/jpeg, gif, webp, avif, svg, mp4 and webm. An SVG is served with `Content-Security-Policy: sandbox`.
- An asset is served to admins at a URL that mirrors its location, such as `/admin/docs/chief::paginas/status-knop.png`, with `?v=<mtime>` appended when a guide renders it.
- An asset follows the visibility of its section, the nearest folder holding it that is a section, so assets of a hidden or role-restricted section return a 404. An asset in a folder without guides, such as `paginas/schermen/`, belongs to the section around it.
- The project replaces a shipped asset with a file at the same path in `resources/docs/vendor/<namespace>/`.
- An image on a line of its own renders at full width with its alt text as caption, and opens in a lightbox when clicked. A path ending in `.mp4` or `.webm` renders as a muted video with controls, and its alt text as fallback.
- Full URLs and root-relative paths are left as written. A relative image that doesn't exist, lies outside the source or isn't visible to the admin renders as its alt text.

### Rendering

Guides are GitHub-flavoured Markdown. Raw HTML is stripped and unsafe links are blocked. The page shows the guide's title itself, so the body's first `# H1` is left out.

- `##` and `###` headings get an ID slugged to ASCII (`## Hoe publiceer ik?` becomes `#hoe-publiceer-ik`) and a `#` permalink that shows on hover.
- Links to another host open in a new window with `rel="noopener"`. Links within the admin don't.
- GitHub alerts render as Chief callouts:

```markdown
> [!TIP]
> Kies waar mogelijk een interne pagina.
```

`[!TIP]` is labelled "Tip", `[!NOTE]` "Goed om te weten" and `[!WARNING]` "Let op". The marker sits on its own line; a plain `> Tip:` stays an ordinary blockquote.

A project extends the rendering from a service provider with `Markdown::configureUsing()`, which receives the CommonMark environment before each guide is converted:

```php
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\SmartPunct\SmartPunctExtension;
use Thinktomorrow\Chief\Plugins\Docs\Markdown;

Markdown::configureUsing(fn (Environment $environment) => $environment->addExtension(new SmartPunctExtension));
```

Prefer this over rebinding `Markdown` in the container, which replaces Chief's rendering as a whole.
