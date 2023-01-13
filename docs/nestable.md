# nestable

Om een pagina nestable te maken dient:
- database aanpassing: toevoegen van een parent_id column. Voorbeeld: ...
- trait aan model toevoegen NestableDefaults
- volgende methods moeten worden toegevoegd aan model:

```php
class Single extends Model implements PageContract, PageResource
{
    use PageResourceDefault;
    use PageDefaults{
        response as defaultResponse;
    }
    use NestableDefaults;

    public function isNestable(): bool
    {
        return true;
    }

    /**
     * Pass the nested node as model to the frontend
     */
    public function response(): Response
    {
        $this->setNestedNodeAsModelInView();

        return $this->defaultResponse();
    }

    /**
     * Allows to pass a predefined parent
     * for the creation of a new nested model.
     */
    public function getInstanceAttributes(Request $request): array
    {
        return $this->getNestableInstanceAttributes($request);
    }

    public function fields($model): iterable
    {
        // Field to select parent model
        yield $this->parentNodeSelect($model);
    }

    public function baseUrlSegment(): string
    {
        $locale = app()->getLocale();

        // TODO: fix this for locales as well!!!!!!
        if($this->getParentNode()){
            return $this->getParentNode()->getUrlSlug($locale);
        }

        // THIS WILL CAUSE ERROR SO W'll HAVE TO WORK ON THIS.


        return parent::baseUrlSegment();
    }
}

```
