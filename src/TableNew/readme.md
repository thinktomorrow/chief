# TODO


## Sorters
- ✅ disable default tree sort
- ✅ dont show default sorting in sorter label
- ✅ set own custom default sort
- what is default for non nestable tables?
- allow for easy asc - desc toggle
- allow headers to be sortable + reflect this in the sorting dropdown
- on reset filter click: revert to default sorter
- when filtering in tree sorting mode, dont display arrows, but show breadcrumbs

## Columns
- label method to assign header. Omit headers method altogether
- header sortable
- updated_at as column text: error in valueMap: Cannot access offset of type Illuminate\Support\Carbon on array

## Pagination
- ✅ BUG: in Hanolux catalogpages points to hanolux.test instead of /admin/catalogpage 
- ✅ tree sorting paginering

## Query
- tree perf: https://chatgpt.com/c/33fad494-cb94-44c0-95eb-10d7e9c92d82
- volgorde in tree sorting in hanolux klopt nog niet...
- Setting tree sorting (in Table::query()) should also be done when a custom query is passed like Page::online() instead of the resourcekey.
