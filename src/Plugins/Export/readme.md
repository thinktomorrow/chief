# Export
This plugin allows to export and import translations.

## Install
Install the package dependency
```bash
composer require maatwebsite/excel
```

Add the Plugin Service Provider to the config app providers
```php
\Thinktomorrow\Chief\Plugins\Export\ExportServiceProvider::class,
```

## Import Tip
Always make an export at the moment of import first.
This serves as a backup and as a way to 'revert' to previous state. So no undo but rather a redo.

