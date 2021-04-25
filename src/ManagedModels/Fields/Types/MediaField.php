<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Fields\Types;

use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\AssetLibrary\Asset;
use Thinktomorrow\AssetLibrary\HasAsset;
use Thinktomorrow\Chief\ManagedModels\Fields\Field;

abstract class MediaField extends AbstractField implements Field
{
    use AllowsMultiple;

    protected array $customValidationRules = [];

    protected string $localizedFormat = 'files.:name.:locale';

    /** @var null|string */
    private $storageDisk;

    /** @var null|callable */
    private $customUrlGenerator;

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

    public function isStoredOnPublicDisk(): bool
    {
        // We assume the default
        $disk = $this->getStorageDisk() ?: config('filesystems.default');

        $fileSettings = config('filesystems.disks.'. $disk);

        return (isset($fileSettings['visibility']) && $fileSettings['visibility'] == "private") ? false : true;
    }

    public function generatesCustomUrl(): bool
    {
        return ! is_null($this->customUrlGenerator) && is_callable($this->customUrlGenerator);
    }

    public function generateCustomUrl(Asset $asset, Model $model = null): string
    {
        return call_user_func_array($this->customUrlGenerator, [$asset, $model]);
    }

    public function setCustomUrlGenerator(callable $callback): Field
    {
        $this->customUrlGenerator = $callback;

        return $this;
    }
}
