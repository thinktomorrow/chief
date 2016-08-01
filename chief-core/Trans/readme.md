# Basics
- TDD
- V hierarchy: first our trans than default trans files. Never touch the original translation files under /resources
- V extend translation class from laravel
- V save a translation
- V remove a translation
- V use cached version to reduce database calls: observe changes
- V save cached files in /storage/frameworks/cache/trans the same way as lang structured files

# EXTRA
- allow html
- compare mode:  between langs (column mode vs stack mode)
- only dev can delete a entire key
- exclude groups from UI (webmasters)
- different management for devs - client
- check for missing translations
- provide CLI interface for developers
- scan for unused tags
- export / import from csv
- import / export as translation files with overwrite security (confirm overwrites)
- dev: change key and sync with usages in application
