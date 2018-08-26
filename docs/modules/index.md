---
layout: default
title: Modules
description: chief is a package based cms built on top of the laravel framework.
navigation_weight: 4
---
# Modules

## Creating modules
To create a module you make a file in the src/Modules folder.
These modules should extend 'Thinktomorrow\Chief\Modules\Module'.

To define the name of this module in the admin panel you can define the 'labelSingular' and 'labelPlural' property

```php
<?php

namespace Project\Modules;

use Thinktomorrow\Chief\Common\Collections\CollectionDetails;
use Thinktomorrow\Chief\Modules\Module;

class Hero extends Module
{
    protected $labelSingular = 'hoofding';
    protected $labelPlural   = 'hoofdings';
}
```

The next thing to do is to add a reference to this module in the chief.php config file.

You will most often define it in 2 places in this config file.
You need to define the module in the collections array so the admin has knowledge of this new module.

The second place might not always be needed. If we define the module in the relations>children array
the module will be available to add to a page.

```php
'relations'   => [

        'children' => [
            \Thinktomorrow\Chief\Pages\Page::class,
            
            Hero::class,
            ...
        ],
        ...
    ],

    /**
     * Here you should provide the mapping of page and module collections. This
     * is required for the class mapping from database to their respective classes.
     */
    'collections' => [
        ...
        // Modules
        'heros'        => Hero::class,
        ...
    ],
```

## Customizing modules
Next to the module model where we can set the names to be used, we can also customize the view that would be used.

To create a view for this specific module, we create views/front/modules/hero.blade.php in this case.
The name of the view should be the same as the module type.

In this view we have access to the $module variable.
And there's a few thing we have access to throught that.

We can get the title, content, and if we have media files attached we can retrieve them as you can see in the example below.

```php
<div class="hero" style="background: url({{ $module->mediaUrl(\Thinktomorrow\Chief\Media\MediaType::BACKGROUND) }}) no-repeat; background-size:cover; background-position:center;">
    <div class="container hero-title">
        <h1 class="text-white text-shade-on-white">{{ $module->title }}</h1>
        @if($module->content)
            <div class="editor-content text-white text-shade-on-white text-2xl">
                {!! $module->content !!}
            </div>
        @endif
    </div>
</div>
```

## Using Modules
Once this setup is done we can create instances of these modules from the admin panel.
And following that to add them to a page, simple select them from the pagebuilder dropdown.