<?php

namespace Thinktomorrow\Chief\Models\App\Actions;

class CreateModel
{
    private string $modelClass;

    private array $allowedSites;

    private array $input;

    private array $files;

    public function __construct(string $modelClass, array $allowedSites, array $input, array $files = [])
    {
        $this->modelClass = $modelClass;
        $this->allowedSites = $allowedSites;
        $this->input = $input;
        $this->files = $files;
    }

    public function getModelClass(): string
    {
        return $this->modelClass;
    }

    public function getAllowedSites(): array
    {
        return $this->allowedSites;
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
