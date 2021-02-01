# admin navigation

## Adding navigation via tags
You can control the navigation links via tags.
Out of the box, the chief navigation displays the index links for models registered with a tag: `nav`, `nav-page` or `nav-company`. 
```
// Register a model in the boot method of your AppServiceProvider with the 'nav' tag.
class AppServiceProvider {
    public function boot(){
        chiefRegister()->model(Article::class, PageManager::class, 'nav');
    }
}
```

- `nav`: adds the model link inline in the navigation
- `nav-page`: adds the model link in a _Pagina's_ dropdown. 
- `nav-company`: adds the model link in a _Bedrijf_ dropdown.

## Changing the navigation label
The url of the navigation item is the index admin route of the model. This is currently fixed and not configurable.
Note that only models that have an _index_ route will be shown as link. Other ones, such as static fragments, won't be added automatically in the navigation. 

The label of the navigation can be set via the models' adminLabel method. Provide a *nav_label* value to control the navigation link label.

## Customising the navigation
If you'd more control beyond the basic options, you can customize the navigation view by exporting it to your application.
Create a view `resources/views/vendor/chief/resources/views/layout/nav/nav-project.blade.php`.
Place into this file the contents of `vendor/thinktomorrow/chief/resources/views/layout/nav/nav-project.blade.php`.
You can then edit your own version to modify the chief navigation.

In it's simplest form, the html of an navigation item is pretty basic:
```html
<li><a href="#">Navigation item</a></li> 
```

## <x-chief::nav> blade component
Chief also provides a `<x-chief::nav>` blade component. In fact the original navigation makes full use of this component:
```
// The original contents of the nav-project view.
<x-chief::nav title="Pagina's" tagged="nav-page"></x-chief::nav>
<x-chief::nav title="Bedrijf" tagged="nav-company"></x-chief::nav>
<x-chief::nav tagged="nav" inline></x-chief::nav>
``` 

The component allows to fetch model links based on tags. Use the `tagged` attribute to find models for one or more tags.
Multiple tags are separated by a comma, e.g.: `<x-chief::nav tagged="catalog,nav-company">`.
Use the `untagged` attribute to fetch those models that have no tags at all. 
```
<x-chief::nav untagged title="Others"></x-chief::nav>
```

By default a nav component shows its links inside a dropdown. Use the `ìnline` attribute if you want to show all links next to each other in the navigation, instead of in a dropdown.
The title attribute controls the dropdown title.
