# nestable

Om een pagina nestable te maken dient:
- interface Nestable
- database aanpassing: toevoegen van een parent_id column. Voorbeeld: ...
- trait aan model toevoegen NestableDefaults
- volgende methods moeten worden toegevoegd aan model:

```php
class Single extends Model implements PageContract, PageResource, \Thinktomorrow\Chief\Shared\Concerns\Nestable\Model\Nestable
{
    use PageResourceDefault;
    use NestablePageDefault;

    public function fields($model): iterable
    {
        // Field to select parent model
        yield $this->parentNodeSelect($model);
    }
}

```
