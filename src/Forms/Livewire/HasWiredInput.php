<?php

namespace Thinktomorrow\Chief\Forms\Livewire;

use Thinktomorrow\Chief\Forms\Fields\Common\FormKey;

trait HasWiredInput
{
    public function formDataIdentifier(string $name, ?string $locale = null): string
    {
        $prefix = (isset($this->prefix) ? $this->prefix.'.' : '');

        return "formData.{$prefix}{$this->formDataIdentifierSegment($name,$locale)}";
    }

    private function formDataIdentifierSegment(string $name, ?string $locale = null): ?string
    {
        $name = FormKey::replaceBracketsByDots($name);

        return $name.(isset($locale) ?'.'.$locale : null);
    }
}
