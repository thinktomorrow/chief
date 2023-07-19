<?php

namespace Thinktomorrow\Chief\Plugins\HotSpots;

use Illuminate\Support\Arr;
use Livewire\Component;
use Thinktomorrow\AssetLibrary\Asset;
use Thinktomorrow\Chief\Assets\App\FileApplication;
use Thinktomorrow\Chief\Assets\Livewire\PreviewFile;
use Thinktomorrow\Chief\Assets\Livewire\Traits\ShowsAsDialog;
use Thinktomorrow\Chief\Forms\Fields\Common\FormKey;
use Thinktomorrow\Chief\Forms\Fields\Field;
use Thinktomorrow\Chief\Forms\Fields\Validation\ValidationParameters;
use Thinktomorrow\Chief\Forms\Livewire\LivewireFieldName;

class HotSpotComponent extends Component
{
    use ShowsAsDialog;

    public $parentId;

    public ?PreviewFile $previewFile = null;
    public $form = [];
    public $components = [];
    public $file = null;

    public function mount(string $parentId, array $components = [])
    {
        $this->parentId = $parentId;

        $this->components = array_map(fn ($component) => $component->toLivewire(), $components);
    }

    public function getListeners()
    {
        return [
            'open' => 'open',
            'open-' . $this->parentId => 'open',
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
        $this->extractFormFromPreviewFile();
    }

    private function extractFormFromPreviewFile()
    {
        $this->form['basename'] = $this->previewFile->getBaseName();

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

    private function syncForm()
    {
        $this->previewFile->fieldValues = $this->form;

        $this->form['basename'] = $this->previewFile->getBaseName();
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

    public function submit()
    {
        $this->validateForm();

        if($this->previewFile->mediaId) {
            app(FileApplication::class)->updateFileName($this->previewFile->mediaId, $this->form['basename']);
            app(FileApplication::class)->updateAssociatedAssetData($this->modelReference, $this->fieldKey, $this->locale, $this->previewFile->mediaId, $this->form);
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
        $this->syncForm();

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

                $component->name(FormKey::replaceDotsByBrackets(LivewireFieldName::get($component->getName())));

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
        return view('chief-hotspots::hotspot-component', [
            //
        ]);
    }
}
