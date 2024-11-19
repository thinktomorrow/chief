<?php

namespace Thinktomorrow\Chief\Table\Columns\Concerns;

use Closure;
use Thinktomorrow\Chief\Table\Columns\ColumnItem;

trait HasVariantMapping
{
    private ?Closure $variantMapResolver = null;

    public function mapVariant(array|Closure $variantMapResolver): static
    {
        if ($variantMapResolver instanceof Closure) {
            $this->variantMapResolver = $variantMapResolver;
        } else {
            $this->variantMapResolver = function (ColumnItem $columnItem) use ($variantMapResolver) {

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
        if ($this->variantMapResolver) {
            call_user_func($this->variantMapResolver, $columnItem, $columnItem->getValue(), $this->getModel(), $this);
        }
    }
}
