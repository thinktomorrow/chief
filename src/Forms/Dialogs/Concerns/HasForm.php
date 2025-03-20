<?php

namespace Thinktomorrow\Chief\Forms\Dialogs\Concerns;

use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Assets\Livewire\PreviewFile;
use Thinktomorrow\Chief\Forms\Fields\Field;
use Thinktomorrow\Chief\Forms\Fields\FieldName\FieldNameHelpers;
use Thinktomorrow\Chief\Forms\Fields\FieldName\LivewireFieldName;
use Thinktomorrow\Chief\Forms\Fields\Validation\ValidationParameters;

trait HasForm
{
    public array $form = [];

    public function addFormData(array $data): void
    {
        $this->form = array_merge($this->form, $data);
    }

    public function setFormData(array $data): void
    {
        $this->form = $data;
    }

    public function getFormData(): array
    {
        return $this->form;
    }

    public function getFormValue(string $key): mixed
    {
        return data_get($this->form, $key);
    }

    public function setFormValue(string $key, mixed $value): void
    {
        data_set($this->form, $key, $value);
    }

    /**
     * Validation is performed for all fields
     * Each field is parsed for the proper validation rules and messages.
     */
    private function validateForm(array $rules = [], array $messages = [], array $validationAttributes = []): void
    {
        [$rules, $messages, $validationAttributes] = $this->createValidation($rules, $messages, $validationAttributes);

        if (! $rules) {
            return;
        }

        $this->validate($rules, $messages, $validationAttributes);
    }

    private function createValidation(array $rules, array $messages, array $validationAttributes): array
    {
        foreach ($this->getFieldsForValidation() as $field) {
            $validationParameters = ValidationParameters::make($field)->mapKeys(fn ($key) => LivewireFieldName::get($key));

            $rules = array_merge($rules, $validationParameters->getRules());
            $messages = array_merge($messages, $validationParameters->getMessages());
            $validationAttributes = array_merge($validationAttributes, $validationParameters->getAttributes());
        }

        return [$rules, $messages, $validationAttributes];
    }

    private function getFieldsForValidation(): array
    {
        return collect($this->getFields())
            ->reject(fn ($field) => ! $field instanceof Field)
            ->all();
    }

    protected function getFilesForUpload(): Collection
    {
        return collect($this->component->getPreviewFiles())->reject(fn (PreviewFile $file) => ($file->isAttachedToModel || $file->isUploading || $file->isQueuedForDeletion || $file->mediaId));
    }

    protected function getFilesForAttach(): Collection
    {
        return collect($this->component->getPreviewFiles())->filter(fn (PreviewFile $file) => ($file->mediaId && ! $file->isQueuedForDeletion));
    }

    protected function getFilesForDeletion(): Collection
    {
        return collect($this->component->getPreviewFiles())->filter(fn (PreviewFile $file) => ($file->isAttachedToModel && $file->isQueuedForDeletion));
    }

    /**
     * Available listener for when file upload component is updated,
     * and dispatches an files-updated event. You can add it as:
     *
     * public function getListeners() {
     *      return ['files-updated' => 'onfilesUpdated'];
     * }
     */
    public function onFilesUpdated(string $fieldName, array $files, ?string $parentComponentId = null)
    {
        // Make sure that the FileFieldUploadComponent is a child component of this component
        if ($this->getId() !== $parentComponentId) {
            return;
        }

        $key = FieldNameHelpers::replaceBracketsByDots($fieldName);

        $previewFiles = collect($files)
            ->map(fn ($file) => PreviewFile::fromLivewire($file));

        $filesForUpload = $previewFiles->reject(fn (PreviewFile $file) => ($file->isAttachedToModel || $file->isUploading || $file->isQueuedForDeletion || $file->mediaId));
        $filesForAttach = $previewFiles->filter(fn (PreviewFile $file) => ($file->mediaId && ! $file->isQueuedForDeletion));
        $filesForDeletion = $previewFiles->filter(fn (PreviewFile $file) => ($file->isAttachedToModel && $file->isQueuedForDeletion));

        // Convert to array that our UpdateFileField action expects
        $this->setFormValue($key, [
            'uploads' => $filesForUpload->map(fn (PreviewFile $file) => $file->toFormPayload())->toArray(),
            'attach' => $filesForAttach->map(fn (PreviewFile $file) => $file->toFormPayload())->toArray(),
            'queued_for_deletion' => $filesForDeletion->map(fn (PreviewFile $file) => $file->id)->toArray(),
            'order' => $previewFiles->map(fn (PreviewFile $file) => $file->id)->toArray(),
        ]);
    }
}
