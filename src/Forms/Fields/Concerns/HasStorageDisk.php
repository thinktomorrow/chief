<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields\Concerns;

trait HasStorageDisk
{
    private ?string $storageDisk = null;

    /**
     * Store the file on a different disk than the default one
     *
     * @return $this
     */
    public function storageDisk(string $disk): static
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
        $fileSettings = config('filesystems.disks.'.
            ($this->getStorageDisk() ?: config('filesystems.default'))
        );

        return (isset($fileSettings['visibility']) && $fileSettings['visibility'] == "private") ? false : true;
    }
}
