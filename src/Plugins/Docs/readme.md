# Docs

A Chief plugin to add user docs to your Chief admin.

## Install

1. Add the DocsServiceProvider to your list of providers
2. Add the docs to the Chief navigation. This can be done by adding the following code to your nav-project file

```php
<x-chief::nav.item
    label="Documentatie"
    url="{{ route('chief.docs.index') }}"
    :is-active="request()->routeIs('chief.docs.*')"
    icon='<svg viewBox="0 0 24 24" color="currentColor" fill="none"> <path d="M18.058 8.53645L17.058 7.92286C16.0553 7.30762 15.554 7 15 7C14.446 7 13.9447 7.30762 12.942 7.92286L11.942 8.53645C10.9935 9.11848 10.5192 9.40949 10.2596 9.87838C10 10.3473 10 10.9129 10 12.0442V17.9094C10 19.8377 10 20.8019 10.5858 21.4009C11.1716 22 12.1144 22 14 22H16C17.8856 22 18.8284 22 19.4142 21.4009C20 20.8019 20 19.8377 20 17.9094V12.0442C20 10.9129 20 10.3473 19.7404 9.87838C19.4808 9.40949 19.0065 9.11848 18.058 8.53645Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /> <path d="M14 7.10809C13.3612 6.4951 12.9791 6.17285 12.4974 6.05178C11.9374 5.91102 11.3491 6.06888 10.1725 6.3846L8.99908 6.69947C7.88602 6.99814 7.32949 7.14748 6.94287 7.5163C6.55624 7.88513 6.40642 8.40961 6.10679 9.45857L4.55327 14.8971C4.0425 16.6852 3.78712 17.5792 4.22063 18.2836C4.59336 18.8892 6.0835 19.6339 7.5 20" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /> <path d="M14.4947 10C15.336 9.44058 16.0828 8.54291 16.5468 7.42653C17.5048 5.12162 16.8944 2.75724 15.1836 2.14554C13.4727 1.53383 11.3091 2.90644 10.3512 5.21135C10.191 5.59667 10.0747 5.98366 10 6.36383" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" /> </svg>'
    collapsible
/>
```

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

A Chief plugin adds its own namespaced guides from its service provider with `loadDocsFrom()`:

```php
$this->loadDocsFrom(__DIR__.'/resources/docs', 'acme');
```

Chief's plugins ship their guides this way under the `admin-toast`, `tags`, `time-table`, `hot-spots`, `seo` and `external-files` namespaces. A plugin guide can attach under a Chief section with `parent` (`parent: chief::media`).

A package or project plugin whose provider does not extend `ChiefPluginServiceProvider` adds its guides from any service provider:

```php
app(\Thinktomorrow\Chief\Plugins\Docs\DocSources::class)->add('acme', __DIR__.'/../resources/docs');
```
