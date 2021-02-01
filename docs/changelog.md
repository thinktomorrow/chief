# Changelog for 0.6


## Manager requests
- Removed: the following optional hooks are no longer available: beforeStore, afterStore, beforeUpdate and afterUpdate. It used to be possible to hook into the request flow by adding one of these
methods to your Manager class. Since a Manager now completely controls the request flow, this is no longer required. 
- Removed: Own Translatable trait. This trait served as a wrapper around the Astrotomic Translatable package. From now on you are advised to use the Astrotomic methods directly.
- Removed: One of the most used method of the removed translatable trait is the method `availableLocales`. This was used to retrieve all the chief locales. From now on you should use `config('thinktomorrow.chief.locales')` to retrieve them.

