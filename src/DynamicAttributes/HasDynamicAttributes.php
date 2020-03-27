<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\DynamicAttributes;

trait HasDynamicAttributes
{
    public function dynamic(string $key, string $index = null)
    {
        return $this->dynamicAttributesInstance()->get($index ? "$key.$index" : $key);
    }

    public function setDynamic(string $key, $value, string $index = null)
    {
        return $this->dynamicAttributesInstance()->set($index ? "$key.$index" : $key, $value);
    }

    public function isDynamicKey($key): bool
    {
        if (array_key_exists($key, $this->attributes)) {
            return false;
        }

        if (in_array($key, $this->dynamicKeys())) {
            return true;
        }

        if (in_array('*', $this->dynamicKeys())) {
            return !in_array($key, $this->dynamicKeysBlacklist());
        }

        return false;
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

    protected function dynamicLocales(): array
    {
        return property_exists($this, 'dynamicLocales') ? $this->dynamicLocales : [];
    }

    /**
     * When allowing by default for all attributes to be dynamic, you can use
     * the blacklist to mark certain attributes as non dynamic.
     *
     * @return array
     */
    protected function dynamicKeysBlacklist(): array
    {
        return property_exists($this, 'dynamicKeysBlacklist') ? $this->dynamicKeysBlacklist : [];
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

        if ($this->dynamicAttributesInstance()->has("$key.{$locale}")) {
            return $this->dynamic("$key.{$locale}");
        }

        if ($this->dynamicAttributesInstance()->has($key)) {
            $value = $this->dynamic($key);

            // If value is localized, we wont return the entire value, but instead return null since no fallback will be provided.
            if (is_array($value) && in_array($locale, $this->dynamicLocales())) {
                return null;
            }

            return $value;
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
