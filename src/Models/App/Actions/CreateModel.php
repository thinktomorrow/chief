<?php

namespace Thinktomorrow\Chief\Models\App\Actions;

class CreateModel
{
    private string $modelClass;

    private array $locales;

    private array $input;

    private array $files;

    public function __construct(string $modelClass, array $locales, array $input, array $files = [])
    {
        $this->modelClass = $modelClass;
        $this->locales = $locales;
        $this->input = $input;
        $this->files = $files;
    }

    public function getModelClass(): string
    {
        return $this->modelClass;
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
