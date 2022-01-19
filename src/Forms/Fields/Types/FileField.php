<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields\Types;

use Thinktomorrow\AssetLibrary\Asset;
use Thinktomorrow\AssetLibrary\HasAsset;
use Thinktomorrow\Chief\Forms\Fields\Field;

class FileField extends MediaField implements Field
{
    private string $uploadButtonLabel = 'Document opladen';

    protected array $customValidationRules = [
        'required' => 'filefield_required',
        'mimetypes' => 'filefield_mimetypes',
        'dimensions' => 'filefield_dimensions',
        'min' => 'filefield_min',
        'max' => 'filefield_max',
    ];

    public static function make(string $key): Field
    {
        return (new static(new FieldType(FieldType::FILE), $key))
            ->locales([config('app.fallback_locale', 'nl')])
            ->valueResolver(function ($model = null, $locale = null, FileField $field) {
                return $field->getMedia($model, $locale);
            });
    }

    /**
     * @return \stdClass[]
     *
     * @psalm-return list<\stdClass>
     */
    public function getMedia(HasAsset $model = null, ?string $locale = null)
    {
        if (! $model) {
            return [];
        }

        $files = [];
        $locale = $locale ?? app()->getLocale();

        $assets = $model->assetRelation->where('pivot.type', $this->getKey())->filter(function ($asset) use ($locale) {
            return $asset->pivot->locale == $locale;
        })->sortBy('pivot.order');

        /** @var Asset $asset */
        foreach ($assets as $asset) {
            $url = $this->isStoredOnPublicDisk()
                ? $asset->url()
                : ($this->generatesCustomUrl() ? $this->generateCustomUrl($asset, $model) : '');

            $thumbUrl = $this->isStoredOnPublicDisk()
                ? $asset->url('thumb')
                : '';

            // If the conversions haven't run yet, we'll use the original image until they are uploaded
            if ($this->isStoredOnPublicDisk() && ! file_exists(public_path($thumbUrl))) {
                $thumbUrl = $asset->url();
            }

            $files[] = (object)[
                'id' => $asset->id,
                'filename' => $asset->filename(),
                'url' => $url,
                'thumbUrl' => $thumbUrl,
                'mimetype' => $asset->getMimeType(),
                'isImage' => ($asset->getExtensionType() == 'image'),
                'size' => $asset->getSize(),
            ];
        }

        return $files;
    }

    /**
     * @return static
     */
    public function uploadButtonLabel(string $uploadButtonLabel): self
    {
        $this->uploadButtonLabel = $uploadButtonLabel;

        return $this;
    }

    public function getUploadButtonLabel(): string
    {
        return $this->uploadButtonLabel;
    }
}
