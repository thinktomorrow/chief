<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Concerns;

trait Enablable
{
    public function isEnabled(): bool
    {
        return (int)$this->{$this->getEnabledField()} === $this->getEnabledValue();
    }

    public function disable()
    {
        $this->{$this->getEnabledField()} = $this->getDisabledValue();
        $this->save();
    }

    public function enable()
    {
        $this->{$this->getEnabledField()} = $this->getEnabledValue();
        $this->save();
    }

    public function scopeEnabled($query)
    {
        $query->where($this->enablable_field, $this->getEnabledValue());
    }

    private function getEnabledField()
    {
        if (property_exists($this, 'enablable_field')) {
            return $this->enablable_field;
        }

        // Sensible default - we assume a attribute by 'enabled'.
        return 'enabled';
    }

    private function getEnabledValue()
    {
        if (property_exists($this, 'enablable_enabled')) {
            return $this->enablable_enabled;
        }

        return 1;
    }

    private function getDisabledValue()
    {
        if (property_exists($this, 'enablable_disabled')) {
            return $this->enablable_disabled;
        }

        return 0;
    }
}
