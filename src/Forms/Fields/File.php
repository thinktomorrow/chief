<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields;

use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\AssetLibrary\Asset;
use Thinktomorrow\AssetLibrary\HasAsset;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasCustomUrl;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasEndpoint;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasMultiple;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasStorageDisk;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasUploadButtonLabel;
use Thinktomorrow\Chief\Forms\Fields\Media\Application\FileUpload;
use Thinktomorrow\Chief\Forms\Fields\Media\FileDTO;
use Thinktomorrow\Chief\Forms\Fields\Validation\MapValidationRules;
use Thinktomorrow\Chief\Managers\Manager;

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

    protected string $view = 'chief-forms::fields.file';
    protected string $windowView = 'chief-forms::fields.file-window';
    protected string $localizedFormKeyTemplate = 'files.:name.:locale';

    public function __construct(string $key)
    {
        parent::__construct($key);

        $this->locales([config('app.fallback_locale', 'nl')]);

        $this->uploadButtonLabel('Bestand opladen');

        $this->value(function ($model, $locale, self $field) {
            return $field->getMedia($model, $locale);
        });

        $this->save(function ($model, $field, $input, $files) {
            app(FileUpload::class)->handle($model, $field, $input, $files);
        });
    }

    public function fill(Manager $manager, Model $model): void
    {
        $this->endpoint($manager->route('asyncUploadFile', $this->getKey(), $this->getModel()?->id));
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

    private function getMedia(Model & HasAsset $model, string $locale): array
    {
        $files = [];

        $assets = $model->assetRelation->where('pivot.type', $this->getKey())->filter(function ($asset) use ($locale) {
            return $asset->pivot->locale == $locale;
        })->sortBy('pivot.order');

        /** @var Asset $asset */
        foreach ($assets as $asset) {
            $files[] = FileDTO::fromAsset($this, $asset);
        }

        return $files;
    }
}
