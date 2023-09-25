<?php

namespace Thinktomorrow\Chief\Forms\Fields\Concerns;

trait AllowsExternalFiles
{
    private bool $allowExternalFiles = false;

    /**
     * On a file upload, does this field allows the user to add external
     * links such as vimeo, youtube to this field assets collection?
     *
     * This will only be possible if at least one external driver is added
     * to the project.
     *
     * @return $this
     */
    public function allowExternalFiles(bool $allowExternalFiles = true): static
    {
        $this->allowExternalFiles = $allowExternalFiles;

        return $this;
    }

    public function getAllowExternalFiles(): bool
    {
        return $this->allowExternalFiles;
    }
}
