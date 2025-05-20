<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Validation\Factory;
use Thinktomorrow\AssetLibrary\Asset;
use Thinktomorrow\AssetLibrary\AssetContract;
use Thinktomorrow\AssetLibrary\HasAsset;
use Thinktomorrow\Chief\Assets\App\Http\LivewireUploadedFile;
use Thinktomorrow\Chief\Forms\App\Actions\SaveFileField;
use Thinktomorrow\Chief\Forms\Concerns\HasComponents;
use Thinktomorrow\Chief\Forms\Concerns\WithComponents;
use Thinktomorrow\Chief\Forms\Fields\Concerns\AllowsExternalFiles;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasAcceptedMimeTypes;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasAssetType;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasCustomUrl;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasEndpoint;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasStorageDisk;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasUploadButtonLabel;
use Thinktomorrow\Chief\Forms\Fields\Concerns\Select\HasMultiple;
use Thinktomorrow\Chief\Forms\Fields\Validation\MapValidationRules;
use Thinktomorrow\Chief\Forms\Fields\Validation\ValidationParameters;
use Thinktomorrow\Chief\Sites\ChiefSites;

/**
 * Default field settings are overriden mostly because values of file inputs
 * are always localized and always considered an array of items.
 */
class File extends Component implements Field, HasComponents
{
    use AllowsExternalFiles;
    use HasAcceptedMimeTypes;
    use HasAssetType;
    use HasCustomUrl;
    use HasEndpoint;
    use HasMultiple;
    use HasStorageDisk;
    use HasUploadButtonLabel;
    use WithComponents;

    protected string $view = 'chief-form::fields.file';

    protected string $previewView = 'chief-form::previews.fields.file';

    public function __construct(string $key)
    {
        parent::__construct($key);
        $this->locales([ChiefSites::primaryLocale()]);
        $this->setFieldNameTemplate('files.:name.:locale');

        $this->uploadButtonLabel('Bestand opladen');

        $this->value(function ($model, $locale, self $field) {
            if (! $model) {
                return [];
            }

            if ($this->useValueFallback) {
                return $model->assets($field->getKey(), $locale)->all();
            }

            return $field->getMedia($model, $locale);
        });

        $this->default([]);

        $this->save(function ($model, $field, $input, $files) {
            app(SaveFileField::class)->handle($model, $field, $input, $files);
        });
    }

    public function getLabel(): ?string
    {
        if (! $label = parent::getLabel()) {
            return null;
        }

        if ($this->getLocales() === [ChiefSites::primaryLocale()]) {
            return $label.' (voor alle talen)';
        }

        return $label;
    }

    private function getMedia(Model&HasAsset $model, string $locale): array
    {
        return $model->assetRelation->where('pivot.type', $this->getKey())->filter(function ($asset) use ($locale) {
            return $asset->pivot->locale == $locale;
        })->sortBy('pivot.order')
            ->all();
    }

    public function createValidatorInstance(Factory $validatorFactory, array $payload): Validator
    {
        $validationParameters = ValidationParameters::make($this)
            ->multiple($this->allowMultiple());

        return $validatorFactory->make(
            $this->preparePayloadForValidation(array_keys($validationParameters->getRules()), $payload),
            $validationParameters->getRules(),
            $validationParameters->getMessages(),
            $validationParameters->getAttributes(),
        );
    }

    private function preparePayloadForValidation(array $ruleKeys, array $payload)
    {
        // For checking the uploads, we convert the livewire upload values to UploadedFile instances first
        $preppedPayload = [];

        foreach ($ruleKeys as $ruleKey) {

            // Ignore empty values
            if (is_null(Arr::get($payload, $ruleKey)) || Arr::get($payload, $ruleKey) === [null]) {
                continue;
            }

            if (Arr::has($payload, $ruleKey.'.uploads') && count(Arr::get($payload, $ruleKey.'.uploads')) > 0) {
                $preppedPayload[$ruleKey] = $this->convertUploadsToUploadedFiles(Arr::get($payload, $ruleKey.'.uploads'));
            } elseif (Arr::has($payload, $ruleKey.'.attach') && count(Arr::get($payload, $ruleKey.'.attach')) > 0) {
                $preppedPayload[$ruleKey] = $this->convertAssetsToUploadedFiles(Arr::get($payload, $ruleKey.'.attach'));
            } // If the asset fields haven't been altered, it means that the files are set as non-assoc array instead of specific uploads, attach, queued_for_deletion,... subarrays.
            elseif (! Arr::has($payload, $ruleKey.'.queued_for_deletion') && count(Arr::get($payload, $ruleKey)) > 0) {
                $preppedPayload[$ruleKey] = $this->convertAssetsToUploadedFiles(Arr::get($payload, $ruleKey));
            }
        }

        return Arr::undot($preppedPayload);
    }

    private function convertUploadsToUploadedFiles(array $uploads): array
    {
        return collect($uploads)
            ->map(fn ($upload) => new LivewireUploadedFile($upload['path'], $upload['originalName'], $upload['mimeType']))
            ->all();
    }

    private function convertAssetsToUploadedFiles(array $attach): array
    {
        $assetIds = collect($attach)->pluck('id')->all();

        return Asset::whereIn('id', $assetIds)->get()
            ->map(fn (AssetContract $asset) => new LivewireUploadedFile($asset->getPath(), $asset->getFileName(), $asset->getMimeType()))
            ->all();
    }

    public function getRules(): array
    {
        return (new MapValidationRules)->handle(parent::getRules(), [
            'required' => 'file_required',
            'mimetypes' => 'file_mimetypes',
            'dimensions' => 'file_dimensions',
            'min' => 'file_min',
            'max' => 'file_max',
        ]);
    }

    /**
     * Since File is always localized, we need to override the default
     * hasLocales to let it check if there are any custom locales set.
     */
    public function showsLocales(): bool
    {
        return $this->hasLocales() && $this->getLocales() !== [ChiefSites::primaryLocale()];
    }
}
