<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Fields\Types;

use Thinktomorrow\AssetLibrary\HasAsset;
use Thinktomorrow\Chief\ManagedModels\Fields\Field;

abstract class MediaField extends AbstractField implements Field
{
    use AllowsMultiple;

    protected array $customValidationRules = [];

    protected string $localizedFormat = 'files.:name.:locale';

    public function validation($rules, array $messages = [], array $attributes = []): Field
    {
        parent::validation($rules, $messages, $attributes);

        $this->validation = $this->validation->customizeRules($this->customValidationRules);

        return $this;
    }

    abstract public function getMedia(HasAsset $model = null, ?string $locale = null);
}