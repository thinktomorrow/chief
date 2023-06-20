<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Validation\Factory;
use Illuminate\Validation\ValidationException;
use Thinktomorrow\AssetLibrary\HasAsset;
use Thinktomorrow\Chief\Assets\App\SaveFileField;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasAcceptedMimeTypes;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasCustomUrl;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasEndpoint;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasMultiple;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasStorageDisk;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasUploadButtonLabel;
use Thinktomorrow\Chief\Forms\Fields\Validation\MapValidationRules;
use Thinktomorrow\Chief\Forms\Fields\Validation\ValidationParameters;

/**
 * Default field settings are overriden mostly because values of file inputs
 * are always localized and always considered an array of items.
 */
class File extends Component implements Field
{
    use HasMultiple;
    use HasStorageDisk;
    use HasCustomUrl;
    use HasUploadButtonLabel;
    use HasEndpoint;
    use HasAcceptedMimeTypes;

    protected string $view = 'chief-form::fields.file';
    protected string $windowView = 'chief-form::fields.file-window';

    public function __construct(string $key)
    {
        parent::__construct($key);

        $this->locales([config('app.fallback_locale', 'nl')]);
        $this->setLocalizedFormKeyTemplate('files.:name.:locale');

        $this->uploadButtonLabel('Bestand opladen');

        $this->value(function ($model, $locale, self $field) {
            if (! $model) {
                return [];
            }

            return $field->getMedia($model, $locale);
        });

        $this->default([]);

        $this->save(function ($model, $field, $input, $files) {
            app(SaveFileField::class)->handle($model, $field, $input, $files);
        });
    }

    public function getRules(): array
    {
        return (new MapValidationRules())->handle(parent::getRules(), [
            'required' => 'file_required',
            'mimetypes' => 'file_mimetypes',
            'dimensions' => 'file_dimensions',
            'min' => 'file_min',
            'max' => 'file_max',
        ]);
    }

    public function createValidatorInstance(Factory $validatorFactory, array $payload): Validator
    {
        $validationParameters = ValidationParameters::make($this)
            ->multiple($this->allowMultiple());

        //        try{
        //            $validatorFactory->make(
        //                $this->preparePayloadForValidation(array_keys($validationParameters->getRules()), $payload),
        //                $validationParameters->getRules(),
        //                $validationParameters->getMessages(),
        //                $validationParameters->getAttributes(),
        //            )->validate();
        //
        //            dd('VALID');
        //        } catch(ValidationException $e)
        //        {
        //            dd($e->getMessage(), $this->preparePayloadForValidation(array_keys($validationParameters->getRules()), $payload),
        //                $validationParameters->getRules());
        //            dd('INVALID');
        //        }

        return $validatorFactory->make(
            $this->preparePayloadForValidation(array_keys($validationParameters->getRules()), $payload),
            $validationParameters->getRules(),
            $validationParameters->getMessages(),
            $validationParameters->getAttributes(),
        );

        // // Validate uploaded File first
        ////        if ($field->hasValidation()) {
        ////
        ////            $payload = [];
        ////
        ////            foreach (array_keys($validationParameters->getRules()) as $fieldKey) {
        ////                $payload[$fieldKey] =
        ////                }
        ////
        ////            $validationParameters = ValidationParameters::make($field);
        ////            dd(
        ////
        ////                $validationParameters->getRules(),
        ////                $validationParameters->getMessages(),
        ////                $validationParameters->getAttributes(),
        ////            );
        ////            $validator = \Illuminate\Support\Facades\Validator::make([$field->getKey() => [$uploadedFile]], [$field->getKey() => $field->getRules()]);
        ////            $validator->validate();
        //////        }
    }

    private function preparePayloadForValidation(array $ruleKeys, array $payload)
    {
        // For checking the uploads, we convert the livewire upload values to UploadedFile instances first
        $preppedPayload = [];

        foreach($ruleKeys as $ruleKey) {
            if(Arr::has($payload, $ruleKey.'.uploads')) {
                $preppedPayload[$ruleKey] = $this->convertUploadsToUploadedFiles(Arr::get($payload, $ruleKey.'.uploads'));
            }
        }

        return Arr::undot($preppedPayload);
    }

    private function convertUploadsToUploadedFiles(array $uploads): array
    {
        return collect($uploads)
            ->map(fn ($upload) => new UploadedFile($upload['path'], $upload['originalName'], $upload['mimeType']))
            ->all();
    }

    private function getMedia(Model & HasAsset $model, string $locale): array
    {
        return $model->assetRelation->where('pivot.type', $this->getKey())->filter(function ($asset) use ($locale) {
            return $asset->pivot->locale == $locale;
        })->sortBy('pivot.order')
        ->all();
    }

    //    public function fill(Manager $manager, Model $model): void
    //    {
    //        $this->endpoint($manager->route('asyncUploadFile', $this->getKey(), $model->{$model->getKeyName()}));
    //    }

    //
    //    private function getLegacyMedia(Model & HasAsset $model, string $locale): array
    //    {
    //        $files = [];
    //
    //        $assets = $model->assetRelation->where('pivot.type', $this->getKey())->filter(function ($asset) use ($locale) {
    //            return $asset->pivot->locale == $locale;
    //        })->sortBy('pivot.order');
    //
    //        /** @var Asset $asset */
    //        foreach ($assets as $asset) {
    //            $files[] = FileDTO::fromAsset($this, $asset);
    //        }
    //
    //        return $files;
    //    }
}
