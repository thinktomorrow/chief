
## MOO
- model, menu and squanto translations
- ook media: asset velden? bv. alt text van een afbeelding
-  Per model allow for an export of the translations
- Also the squanto translations
- The export will be in the form of an excel file
- Multiple models can be combined in an excel as tabs?
- Format should be suitable for guided import (aka check for html tags, urls to be localized,...)

## Import UI
- always make an export first as a backup as a way to 'revert' to previous state. So no undo but rather a redo.
- step 1: please upload your translation import file
- Import first checks validity of the file
- autodetect columns: allow to manually select the columns. This determines the translations
- step 3: preview a couple of the changes. Allow to preview all the changes
- step 4: confirm the import: allow to import each translation line at a time or all at once. 
- step 5: confirmed. show the export file as backup. Also a list of all the changes. 

FORMAT:
PAGETITLE
    - column key
    - dynamic key
fragments...
    - fragment id
    - dynamic key

extra kolom met opmerkingen:
- opgelet bevat html tags. Deze moeten dezelfde blijven, enkel de tekst moet vertaald worden
- opgelet bevat links. Deze zullen na de vertalingen nog worden aangepast en mogen dus dezelfde blijven, enkel de tekst moet vertaald worden


## Install
First install the package dependency
```bash
composer require maatwebsite/excel
```

