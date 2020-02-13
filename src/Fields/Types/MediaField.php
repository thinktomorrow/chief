<?php declare(strict_types=1);

namespace Thinktomorrow\Chief\Fields\Types;

use Thinktomorrow\AssetLibrary\HasAsset;

abstract class MediaField extends AbstractField implements Field
{
    use AllowsMultiple;

    protected $customValidationRules = [];

    protected function getLocalizedNameFormat(): string
    {
        return 'files.:name.:locale';
    }

    public function validation($rules, array $messages = [], array $attributes = []): Field
    {
        parent::validation($rules, $messages, $attributes);

        $this->validation = $this->validation->customizeRules($this->customValidationRules);

        return $this;
    }

    public function getValue($model = null, string $locale = null)
    {
        return $this->getMedia($model, $locale);
    }

    abstract protected function getMedia(HasAsset $model, ?string $locale = null);
}
