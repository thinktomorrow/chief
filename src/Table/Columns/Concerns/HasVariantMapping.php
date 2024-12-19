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

                if(is_scalar($rawValue)) {
                    return $variantMapResolver[$rawValue] ?? $rawValue;
                }

                return $rawValue;
            };
        }

        return $this;
    }

    protected function handleVariantMapping(ColumnItem $columnItem): void
    {
        foreach ($this->variantMapResolvers as $variantMapResolver) {
            $columnItem->variant(
                call_user_func($variantMapResolver, $columnItem->getRawValue(), $columnItem, $this->getModel(), $this)
            );
        }
    }
}
