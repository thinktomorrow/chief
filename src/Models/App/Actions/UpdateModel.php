<?php

namespace Thinktomorrow\Chief\Models\App\Actions;

use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;

class UpdateModel
{
    private ModelReference $modelReference;

    private array $locales;

    private array $input;

    private array $files;

    public function __construct(ModelReference $modelReference, array $locales, array $input, array $files = [])
    {
        $this->modelReference = $modelReference;
        $this->locales = $locales;
        $this->input = $input;
        $this->files = $files;
    }

    public function getModelReference(): ModelReference
    {
        return $this->modelReference;
    }

    public function getLocales(): array
    {
        return $this->locales;
    }

    public function getInput(): array
    {
        return $this->input;
    }

    public function getFiles(): array
    {
        return $this->files;
    }
}
