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

    public function isDynamicKey($key): bool
    {
        if (array_key_exists($key, $this->attributes)) {
            return false;
        }

        return in_array($key, $this->dynamicKeys());
    }

    /**
     * The attribute key which the dynamic attributes is
     * referenced by as well as the database column name.
     *
     * @return string
     */
    protected function getDynamicKey(): string
    {
        return 'values';
    }

    /**
     * The attributes that should be treated as dynamic ones. This
     * is a list of keys matching the database column names.
     * @return array
     */
    protected function dynamicKeys(): array
    {
        return property_exists($this, 'dynamicKeys') ? $this->dynamicKeys : [];
    }

    /**
     * Part of the custom cast. Method used by the save logic on eloquent. We override this so we can
     * inject an json version of the dynamic attributes for saving into the database.
     *
     * @return mixed
     */
    public function getAttributes()
    {
        return $this->injectDynamicAttributes($this->attributes, false);
    }

    /* Part of the custom cast */
    public function setRawAttributes(array $attributes, $sync = false)
    {
        return parent::setRawAttributes($this->injectDynamicAttributes($attributes), $sync);
    }

    /* Part of the custom cast */
    public function fill(array $attributes)
    {
        return parent::fill($this->injectDynamicAttributes($attributes));
    }

    /*
     * If the dynamic attributes contain a localized value, this has preference over any non-localized
     */
    public function getAttribute($key)
    {
        if (!$this->isDynamicKey($key)) {
            return parent::getAttribute($key);
        }

        $locale = app()->getLocale();

        foreach (["{$locale}.$key", $key] as $k) {
            if ($this->dynamicAttributesInstance()->has($k)) {
                return $this->dynamic($k);
            }
        }

        return parent::getAttribute($key);
    }

    /* Part of the custom cast */
    public function setAttribute($key, $value)
    {
        if ($this->isDynamicKey($key)) {
            return $this->setDynamic($key, $value);
        }

        return parent::setAttribute($key, $value);
    }

    /* Part of the custom cast */
    protected function dynamicAttributesInstance(): DynamicAttributes
    {
        if (!isset($this->attributes[$this->getDynamicKey()])) {
            $this->attributes[$this->getDynamicKey()] = DynamicAttributes::fromRawValue([]);
        } elseif (!($raw = $this->attributes[$this->getDynamicKey()]) instanceof DynamicAttributes) {
            $this->attributes[$this->getDynamicKey()] = DynamicAttributes::fromRawValue($raw);
        }

        return $this->attributes[$this->getDynamicKey()];
    }

    /**
     * Inject the dynamic attributes into the attributes array.
     * Either as a DynamicAttributes instance or back to a json format.
     *
     * @param array $attributes
     * @param bool $castToObject
     * @return array
     */
    private function injectDynamicAttributes(array $attributes, bool $castToObject = true): array
    {
        if (isset($attributes[$this->getDynamicKey()])) {
            $attributes[$this->getDynamicKey()] = $castToObject
                ? DynamicAttributes::fromRawValue($attributes[$this->getDynamicKey()])
                : $attributes[$this->getDynamicKey()]->toJson();
        }

        return $attributes;
    }
}
