<?php

namespace Thinktomorrow\Chief\Assets\Livewire;

use Illuminate\Support\Arr;
use Livewire\Component;
use Livewire\WithFileUploads;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Thinktomorrow\AssetLibrary\Asset;
use Thinktomorrow\Chief\Assets\App\FileApplication;
use Thinktomorrow\Chief\Assets\Livewire\Traits\ShowsAsDialog;
use Thinktomorrow\Chief\Forms\Fields\Common\FormKey;
use Thinktomorrow\Chief\Forms\Fields\Field;
use Thinktomorrow\Chief\Forms\Fields\Validation\ValidationParameters;
use Thinktomorrow\Chief\Forms\Livewire\LivewireAssist;

class FileEditComponent extends Component
{
    use ShowsAsDialog;
    use WithFileUploads;

    public $parentId;
    public string $modelReference;
    public string $fieldKey;
    public string $locale;

    public ?PreviewFile $previewFile = null;
    public ?PreviewFile $replacedPreviewFile = null;
    public $form = [];
    public $components = [];
    public $file = null;
    public bool $showReplaceActions = false;

    public function mount(string $modelReference, string $fieldKey, string $locale, string $parentId, array $components = [])
    {
        $this->modelReference = $modelReference;
        $this->fieldKey = $fieldKey;
        $this->locale = $locale;
        $this->parentId = $parentId;

        $this->components = array_map(fn ($component) => $component->toLivewire(), $components);
    }

    public function getListeners()
    {
        return [
            'open' => 'open',
            'open-' . $this->parentId => 'open',
//            'assetsChosen-'.$this->id => 'onAssetsChosen',
        ];
    }

    public function getComponents(): array
    {
        return array_map(function ($componentArray) {
            return $componentArray['class']::fromLivewire($componentArray);
        }, $this->components);
    }

    public function open($value)
    {
        $this->setFile(is_array($value['previewfile']) ? PreviewFile::fromArray($value['previewfile']) : $value['previewfile']);
        $this->isOpen = true;
    }

    public function close()
    {
        $this->reset(['previewFile','form']);
        $this->isOpen = false;
    }

    private function setFile(PreviewFile $previewFile)
    {
        $this->previewFile = $previewFile;
        $this->form['basename'] = $previewFile->getBaseName();

        if($previewFile->mediaId) {
            $mediaModel = Media::find($previewFile->mediaId);
        }

        $this->extractFormFromPreviewFile();
    }

    private function extractFormFromPreviewFile()
    {
        foreach($this->components as $componentArray) {
            $component = $componentArray['class']::fromLivewire($componentArray);

            if(! $component instanceof Field) {
                continue;
            }

            Arr::set(
                $this->form,
                $component->getKey(),
                data_get($this->previewFile->fieldValues, $component->getKey())
            );
        }
    }

    public function updatedFile(): void
    {
        if(! $this->replacedPreviewFile) {
            $this->replacedPreviewFile = $this->previewFile;
        }
        $this->previewFile = PreviewFile::fromTemporaryUploadedFile($this->file);
        $this->syncValues();
    }

    private function syncValues()
    {
        $this->previewFile->fieldValues = $this->form;
        $this->previewFile->filename = $this->form['basename'] . '.' . $this->previewFile->extension;
    }

//    public function onAssetsChosen(array $assetIds)
//    {
//        if(empty($assetIds)) return;
//
//        // Replacement of file can be only one asset.
//        $assetId = reset($assetIds);
//
//        $previewFile = PreviewFile::fromAsset(Asset::where('id', $assetId)->first());
//        $previewFile->isAttachedToModel = false;
//
//        if(!$this->replacedPreviewFile) {
//            $this->replacedPreviewFile = $this->previewFile;
//        }
//
//        $this->previewFile = $previewFile;
//    }

//    public function openFilesChoose()
//    {
//        $this->emitDownTo('chief-wire::files-choose', 'open');
//    }

    public function openImageCrop()
    {
        $this->emitToSibling('chief-wire::image-crop', 'openInParentScope', ['previewfile_array' => $this->previewFile]);
    }

    public function submit()
    {
        $this->validateForm();

        if($this->previewFile->mediaId) {
            app(FileApplication::class)->updateFileName($this->previewFile->mediaId, $this->form['basename']);
            app(FileApplication::class)->updateFieldValues($this->modelReference, $this->fieldKey, $this->locale, $this->previewFile->mediaId, $this->form);
        }

        if($this->replacedPreviewFile) {
            if($this->replacedPreviewFile->mediaId) {
                app(FileApplication::class)->replaceMedia($this->replacedPreviewFile->mediaId, $this->previewFile->toUploadedFile());
                $this->previewFile = PreviewFile::fromAsset(Asset::find($this->replacedPreviewFile->mediaId));
            } else {
                $this->previewFile->id = $this->replacedPreviewFile->id;
            }
        }

        // Update form values
        $this->syncValues();

        $this->emitUp('assetUpdated', $this->previewFile);

        $this->close();
    }

    /**
     * Validation is performed for all fields
     * Each field is parsed for the proper validation rules and messages.
     */
    private function validateForm(): void
    {
        $rules = $messages = $validationAttributes = [];

        foreach ($this->getComponents() as $component) {
            if ($component instanceof Field) {

                $component->name(FormKey::replaceDotsByBrackets(LivewireAssist::formDataIdentifier($component->getName())));

                $validationParameters = ValidationParameters::make($component);
                $rules = array_merge($rules, $validationParameters->getRules());
                $messages = array_merge($messages, $validationParameters->getMessages());
                $validationAttributes = array_merge($validationAttributes, $validationParameters->getAttributes());
            }
        }

        $this->validate($rules, $messages, $validationAttributes);
    }

    public function render()
    {
        return view('chief-assets::file-edit', [
            //
        ]);
    }

    private function emitDownTo($name, $event, array $params = [])
    {
        $this->emitTo($name, $event . '-' . $this->id, $params);
    }

    private function emitToSibling($name, $event, array $params = [])
    {
        $params['parent_id'] = $this->parentId;
        $this->emitTo($name, $event, $params);
    }
}
