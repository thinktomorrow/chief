## Chief Medialibrary

This medialibrary is based on spatie's medialibrary package but provides some extra features and views.

## Features

This media library provides some extra features along those of spatie/media-library.

    - it can upload a file to the library without an attached model
    - it has localization support
    - it can define a type for an upload attached to a model
    - media library page to add/remove/view media files
    - it can upload a file to a model
    - it can attach a file from the library to a model
    - a media file can be attached to multiple models

## Workflow
To make a model accept file uploads we only need to implement the HasMedia interface and use the AssetTrait and HasMediaTrait.

```php
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;

class Article extends Model implements HasMedia
{
    use AssetTrait, HasMediaTrait;
}
```

That's it!

#### Creating files

We can now upload a file to articles like this:

```php
$article->addFile('file', 'type', 'locale');
```

The file is required, the type and locale are optional.
The file van be any file or an instance of Chief\Asset.
The Chief\Asset upload is used to attach existing assets from the library to an existing model, and works exactly the same as uploading a file.

Type allows us to get a file based on the type for instance an article could have a banner but also a pdf file.
Without type the library wouldn't be able to discern between them.

An upload also creates conversions(size) for the file:

    - thumb:    width     150
                height    150
    - medium:   width     300
                height    130
    - large:    width     1024
                height    353
    - full:     width     1600
                height    553

The original version will be returned if you don't specify the size.

To aid you in sending the right data to the controller there are helper functions to inject an input into your form like so:

```php
{!! \Chief\Models\Asset::typeField('banner') !!}
{!! \Chief\Models\Asset::localeField($locale) !!}
```

The type field also has an optional property locale if you need to seperate multiple uploads by locale.

#### Retrieving files

Get all the uploaded files:
```php
Asset::getAllAssets()
``` 
check if the file exists for the given type and locale
```php
$model->hasFile('type','locale') 
```
Get the filename for the given type and locale:
```php
$model->getFilename('type','locale') 

```
Get the url for the given type, size and locale
```php
$model->getFileUrl('type', 'size', 'locale')
```

## Resources
- https://github.com/spatie/laravel-medialibrary
