---
layout: default
title: Menus
description: chief is a package based cms built on top of the laravel framework.
navigation_weight: 5
---
# Menus

- intro
- create a menu
- render a menu

Chief allows you to define multiple menus. And manage them seperatly

## Creating a menu

To add a menu to chief we define one in the chief-setting config file.

```File: Config\chief-settings.php```
```php
'menus' => [
        'main' => [
            'label' => 'Main navigation',
            'view'  => 'front.menus.main'
        ],
        'footer' => [
            'label' => 'Footer navigation',
            'view'  => 'front.menus.footer'
        ]
    ],
```

Define the name of the menu and a label and the corresponding view file.

## Using the menu

To start using the menus you have defined you can use the following handler to define where to render the menu.

```php
{!! chiefmenu('main')->render() !!}
```
Next to access the menu in the defined view file.

```php
@foreach(\Thinktomorrow\Chief\Menu\ChiefMenu::fromMenuItems('main')->items() as $item)
    <li><a href="{{ $item->url }}">{{ $item->label }}</a></li>
@endforeach
```
On the menu items you have access to the url and label of the item.

To find out if the current item has any sub items associated with it use the haschildren check.
And then loop over the children to get each child. Deeper levels may be available depending on how your menu is set up.

```php
@if($item->hasChildren())
    @foreach($item->children() as $child)
        <a href="{{ $child->url }}" class="block text-secondary small-caps">{{ $child->label }}</a>
    @endforeach
@endif
```

## Creating menu items

Now you can go ahead and use the admin panel to manage the menu items for the menus you have defined and set up.