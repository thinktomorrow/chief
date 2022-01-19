<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields\Concerns;

use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\AssetLibrary\Asset;

trait HasCustomUrl
{
    private ?\Closure $customUrlGenerator = null;

    public function generatesCustomUrl(): bool
    {
        return !is_null($this->customUrlGenerator) && is_callable($this->customUrlGenerator);
    }

    public function generateCustomUrl(Asset $asset, Model $model = null): string
    {
        return call_user_func_array($this->customUrlGenerator, [$asset, $model]);
    }

    public function setCustomUrlGenerator(callable $callback): static
    {
        $this->customUrlGenerator = $callback;

        return $this;
    }
}
