<?php

namespace Thinktomorrow\Chief\Table\Columns\Concerns;

use Closure;
use Thinktomorrow\Chief\Table\Columns\ColumnItem;

trait HasVariantMapping
{
    private array $variantMapResolvers = [];

    public function mapVariant(array|Closure $variantMapResolver): static
    {
        if ($variantMapResolver instanceof Closure) {
            $this->variantMapResolvers[] = $variantMapResolver;
        } else {
            $this->variantMapResolvers[] = function ($rawValue, ColumnItem $columnItem) use ($variantMapResolver) {

                if (is_scalar($rawValue)) {
                    // Check for both capitalized and lowercased keys
                    return $variantMapResolver[strtolower($rawValue)] ?? ($variantMapResolver[$rawValue] ?? $rawValue);
                }

                return $rawValue;
            };
        }

        return $this;
    }

    protected function handleVariantMapping(ColumnItem $columnItem): void
    {
        foreach ($this->variantMapResolvers as $variantMapResolver) {

            $value = is_scalar($columnItem->getOriginalValue()) ? $columnItem->getOriginalValue() : $columnItem->getRawValue();

            $columnItem->variant(
                call_user_func($variantMapResolver, $value, $columnItem, $this->getModel(), $this)
            );
        }
    }
}
