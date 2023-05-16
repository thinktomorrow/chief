# Tags

A Chief plugin to add tag functionality in your Chief admin.

## Install

1. Add the TagsServiceProvider to your list of providers
2. Run the tags migration
3. Add the `Thinktomorrow\Chief\Plugins\Tags\Taggable` interface to the models you want to add tags to. Add the `Thinktomorrow\Chief\Plugins\Tags\TaggableDefaults` trait as well.
4. Add the tag field to allow tags to be selected.
5. Add the tagfilter for the index of these models.
6. Add the tags index to the Chief navigation. This can be done by adding the following code to your nav-project file

```php
<x-chief::nav.item
    label="Tags"
    url="{{ route('chief.tags.index') }}"
    icon="<svg><use xlink:href='#icon-rectangle-group'></use></svg>"
    collapsible
/>
```
