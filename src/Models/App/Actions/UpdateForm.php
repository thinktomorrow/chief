<?php

namespace Thinktomorrow\Chief\Models\App\Actions;

use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;

class UpdateForm
{
    private ModelReference $modelReference;

    private string $formId;

    private array $input;

    private array $files;

    public function __construct(ModelReference $modelReference, string $formId, array $input, array $files = [])
    {
        $this->modelReference = $modelReference;
        $this->formId = $formId;
        $this->input = $input;
        $this->files = $files;
    }

    public function getModelReference(): ModelReference
    {
        return $this->modelReference;
    }

    public function getFormId(): string
    {
        return $this->formId;
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
