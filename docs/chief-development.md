---
layout: default
title: Local development
description: chief is a package based cms built on top of the laravel framework.
navigation_weight: 2
---
# Local Chief development

For local development of chief we need another project to include the Chief package into since a package does not contain the whole laravel framework.
To set up the chief package for local development we link our local chief folder as a repository in the composer.json file.

Paste the following snippet in your composer.json file. This can be placed right above the 'require' section.
```php
"repositories":[
       {
           "url":"/full/path/to/chief",
           "type":"path",
           "options":{
               "symlinks":true
           }
       }
   ],
```
The url property needs to be the full path to the local version of the chief package.

Next install the local version of Chief. If you already had an installed chief in your vendor folder, make sure to remove it first, otherwise composer will not force the local symlink.
```php
composer require thinktomorrow/chief:"@dev"
``` 
Make sure your minimum stability of the application is set to `dev`.

To migrate and scaffold some entries you can run:
```php
php artisan chief:refresh
```
**Note that this will remove your existing database entries!**

// TODO: something about adding to semver, changelog...
