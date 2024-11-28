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
            $this->variantMapResolvers[] = function (ColumnItem $columnItem) use ($variantMapResolver) {

                $originalValue = $columnItem->getValue();

                if (is_scalar($originalValue) && isset($variantMapResolver[$originalValue])) {
                    $columnItem->variant($variantMapResolver[$originalValue]);
                }
            };
        }

        return $this;
    }

    protected function handleVariantMapping(ColumnItem $columnItem): void
    {
        foreach($this->variantMapResolvers as $variantMapResolver) {
            call_user_func($variantMapResolver, $columnItem, $columnItem->getValue(), $this->getModel(), $this);
        }
    }
}
