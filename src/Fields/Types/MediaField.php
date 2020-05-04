<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fields\Types;

use Thinktomorrow\AssetLibrary\HasAsset;

abstract class MediaField extends AbstractField implements Field
{
    use AllowsMultiple;

    protected $customValidationRules = [];

    protected $localizedFormat = 'files.:name.:locale';

    public function validation($rules, array $messages = [], array $attributes = []): Field
    {
        parent::validation($rules, $messages, $attributes);

        $this->validation = $this->validation->customizeRules($this->customValidationRules);

        return $this;
    }

    abstract public function getMedia(HasAsset $model = null, ?string $locale = null);
}
