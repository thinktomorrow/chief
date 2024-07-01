
## MOO
- ook media: asset velden? bv. alt text van een afbeelding
-  Per model allow for an export of the translations

## Import UI
- always make an export first as a backup as a way to 'revert' to previous state. So no undo but rather a redo.
- step 1: please upload your translation import file
- Import first checks validity of the file
- autodetect columns: allow to manually select the columns. This determines the translations
- step 3: preview a couple of the changes. Allow to preview all the changes
- step 4: confirm the import: allow to import each translation line at a time or all at once. 
- step 5: confirmed. show the export file as backup. Also a list of all the changes.

## Install
Install the package dependency
```bash
composer require maatwebsite/excel
```

Add the Plugin Service Provider to the config app providers
```php
\Thinktomorrow\Chief\Plugins\Export\ExportServiceProvider::class,
```
