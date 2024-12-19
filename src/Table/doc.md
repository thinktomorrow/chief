# Table docs


## Filters

```php

// Example of a select filter
SelectFilter::make('current_state')->label('Status')
                ->options([
                    '' => 'Alle',
                    'published' => 'online',
                    'draft' => 'offline',
                ])->default(''),

```
