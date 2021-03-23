<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fields\Types;

use Thinktomorrow\AssetLibrary\HasAsset;

abstract class MediaField extends AbstractField implements Field
{
    use AllowsMultiple;

    protected $customValidationRules = [];

    protected $localizedFormat = 'files.:name.:locale';

    /** @var null|string */
    private $storageDisk;

    public function validation($rules, array $messages = [], array $attributes = []): Field
    {
        parent::validation($rules, $messages, $attributes);

        $this->validation = $this->validation->customizeRules($this->customValidationRules);

        return $this;
    }

    abstract public function getMedia(HasAsset $model = null, ?string $locale = null);

    /**
     * Store the file on a different disk than the default one
     *
     * @return $this
     */
    public function storageDisk(string $disk)
    {
        $this->storageDisk = $disk;

        return $this;
    }

    public function getStorageDisk(): ?string
    {
        return $this->storageDisk ?: null;
    }
}
