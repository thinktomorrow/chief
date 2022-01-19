<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields\Concerns;

use Closure;

trait HasUploadButtonLabel
{
    protected null|string|int|array|Closure $uploadButtonLabel = null;

    public function uploadButtonLabel(null|string|int|array|Closure $uploadButtonLabel): static
    {
        $this->uploadButtonLabel = $uploadButtonLabel;

        return $this;
    }

    public function getUploadButtonLabel(?string $locale = null): null|string|int|array
    {
        return $this->getLocalizableProperty($this->uploadButtonLabel, $locale);
    }
}
