<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\DynamicAttributes;

trait HasDynamicAttributes
{
    public function dynamic(string $key, string $index = null)
    {
        return $this->dynamicAttributesInstance()->get($index ? "$index.$key" : $key);
    }

    public function setDynamic(string $key, $value, string $index = null)
    {
        return $this->dynamicAttributesInstance()->set($index ? "$index.$key" : $key, $value);
    }

    protected function shouldBeSetAsDynamicAttribute($key): bool
    {
        if(array_key_exists($key, $this->attributes) || !array_key_exists($this->getDynamicAttributesKey(), $this->attributes)) {
            return false;
        }

        return in_array($key, $this->dynamicAttributeKeys());
    }

    protected function dynamicAttributesInstance(): DynamicAttributes
    {
        if(!($raw = $this->attributes[$this->getDynamicAttributesKey()]) instanceof DynamicAttributes) {
            $this->attributes[$this->getDynamicAttributesKey()] = DynamicAttributes::fromRawValue($raw);
        }

        return $this->attributes[$this->getDynamicAttributesKey()];
    }

    public function isDynamicAttributeKey($key): bool
    {
        return (in_array($key, $this->dynamicAttributeKeys()));
    }

    protected function getDynamicAttributesKey()
    {
        return defined('static::DYNAMIC_ATTRIBUTES_KEY') ? static::DYNAMIC_ATTRIBUTES_KEY : 'values';
    }

    protected function dynamicAttributeKeys(): array
    {
        return property_exists($this, 'dynamicAttributes') ? $this->dynamicAttributes : [];
    }

    /**
     * Method used by the save logic on eloquent. This way we can inject an
     * json version of the dynamic attributes for saving into the database.
     *
     * @return mixed
     */
    public function getAttributes()
    {
        return $this->injectDynamicAttributes($this->attributes, false);
    }

    public function setRawAttributes(array $attributes, $sync = false)
    {
        return parent::setRawAttributes($this->injectDynamicAttributes($attributes), $sync);
    }

    public function fill(array $attributes)
    {
        return parent::fill($this->injectDynamicAttributes($attributes));
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
            return $this->setDynamic($key, $value);
        }

        return parent::setAttribute($key, $value);
    }

    private function injectDynamicAttributes(array $attributes, bool $castToObject = true): array
    {
        if(isset($attributes[$this->getDynamicAttributesKey()])) {
            $attributes[$this->getDynamicAttributesKey()] = $castToObject
                ? DynamicAttributes::fromRawValue($attributes[$this->getDynamicAttributesKey()])
                : $attributes[$this->getDynamicAttributesKey()]->toJson();
        }

        return $attributes;
    }
}
