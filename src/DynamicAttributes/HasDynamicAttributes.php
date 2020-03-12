<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\DynamicAttributes;

trait HasDynamicAttributes
{
    /**
     * Method used by the save logic on eloquent. This way we can inject an json version of the
     *dynamic attributes for saving into the database.
     * @return mixed
     */
    public function getAttributes()
    {
        $attributes = $this->attributes;

        $attributes[$this->getDynamicAttributesKey()] = json_encode($attributes[$this->getDynamicAttributesKey()]->all());

        return $attributes;
    }

    public function setRawAttributes(array $attributes, $sync = false)
    {
        if (isset($attributes[$this->getDynamicAttributesKey()])) {
            /*
             * fill the dynamic attributes in a custom key, so this will not conflict with the original values.
             * A custom key other than the column key is required because the trait is initialized before
             * the original attributes of the model are filled in.
            */
            $attributes[$this->getDynamicAttributesKey()] = $this->convertToDynamicAttributes($attributes[$this->getDynamicAttributesKey()]);
        }

        return parent::setRawAttributes($attributes, $sync);
    }

    public function fill(array $attributes)
    {
        if (isset($attributes[$this->getDynamicAttributesKey()])) {
            /*
             * fill the dynamic attributes in a custom key, so this will not conflict with the original values.
             * A custom key other than the column key is required because the trait is initialized before
             * the original attributes of the model are filled in.
            */
            $attributes[$this->getDynamicAttributesKey()] = $this->convertToDynamicAttributes($attributes[$this->getDynamicAttributesKey()]);
        }

        return parent::fill($attributes);
    }

    private function convertToDynamicAttributes($value): DynamicAttributes
    {
        $value = is_array($value) ? $value : json_decode($value, true);

        /*
         * fill the dynamic attributes in a custom key, so this will not conflict with the original values.
         * A custom key other than the column key is required because the trait is initialized before
         * the original attributes of the model are filled in.
        */
        return new DynamicAttributes((array) $value);
    }

    public function getAttribute($key)
    {
        // Fetching a native models' attribute has precedence over a dynamic attribute.
        if (array_key_exists($key, $this->attributes)){
            return parent::getAttribute($key);
        }

        $locale = app()->getLocale();

        // If the dynamic attributes contain a localized value, this has preference over any non-localized.
        foreach(["{$locale}.$key", $key] as $k){
            if($this->dynamicAttributesInstance()->has($k)){
                return $this->dynamic($k);
            }
        }

        return parent::getAttribute($key);
    }

    public function setAttribute($key, $value)
    {
        if ($this->shouldBeSetAsDynamicAttribute($key)){
            return $this->dynamicAttributesInstance()->set($key, $value);
        }

        return parent::setAttribute($key, $value);
    }

    protected function shouldBeSetAsDynamicAttribute($key): bool
    {
        if(array_key_exists($key, $this->attributes) || !array_key_exists($this->getDynamicAttributesKey(), $this->attributes)) {
            return false;
        }

        return in_array($key, $this->getDynamicAttributes());
    }

    public function dynamic(string $key, string $index = null)
    {
        return $this->dynamicAttributesInstance()->get($index ? "$index.$key" : $key);
    }

    protected function dynamicAttributesInstance(): DynamicAttributes
    {
        return $this->attributes[$this->getDynamicAttributesKey()];
    }

    protected function getDynamicAttributesKey()
    {
        return defined('static::DYNAMIC_ATTRIBUTES_KEY') ? static::DYNAMIC_ATTRIBUTES_KEY : 'values';
    }

    protected function getDynamicAttributes(): array
    {
        return property_exists($this, 'dynamicAttributes') ? $this->dynamicAttributes : [];
    }
}
