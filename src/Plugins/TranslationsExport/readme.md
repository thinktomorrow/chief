
## MOO
- model, menu and squanto translations
- ook media: asset velden? bv. alt text van een afbeelding
-  Per model allow for an export of the translations
- Also the squanto translations
- The export will be in the form of an excel file
- Multiple models can be combined in an excel as tabs?
- Format should be suitable for guided import (aka check for html tags, urls to be localized,...)

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

