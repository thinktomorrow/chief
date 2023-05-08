<?php

namespace Thinktomorrow\Chief\Forms\Fields\File\Livewire;

use Illuminate\Support\Str;
use Livewire\Wireable;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Thinktomorrow\AssetLibrary\Asset;
use Thinktomorrow\Chief\Forms\Fields\File;

class MediaFile implements Wireable
{
    private function __construct(
        public string $mediaId,
        public array $urls,
        public string $filename,
        public ?string $alt,
        public string $size,
        public string $humanReadableSize,
        public string $mimeType,
        public string $extension,
        public array $owners,
    ) {

    }

    public function getBaseName(): string
    {
        return File\App\FileHelper::getBaseName($this->filename);
    }

    public function url(string $conversionName = 'original'): ?string
    {
        return $this->urls[$conversionName] ?? null;
    }

    public function isImage(): bool
    {
        return Str::endsWith($this->mimeType, [
            'png', 'jpg', 'jpeg', 'gif', 'svg', 'webp',
        ]);
    }

    // TODO: this should be changed to fromMedia when we remove asset library
    public static function fromMedia(Media $model): static
    {
        $urls = [
            'original' => $model->originalUrl,
            ...$model->getGeneratedConversions()->reject(fn ($isConverted) => false)->mapWithKeys(fn ($isConverted, $conversionName) => [$conversionName => $model->getUrl($conversionName)])->all(),
        ];

        // Owners
        $owners = [];
        if($asset = $model->model) {

        }

        return new static(
            $model->id,
            $urls,
            $model->file_name,
            $model->getCustomProperty('alt', null),
            $model->size,
            File\App\FileHelper::getHumanReadableSize($model->size),
            $model->mime_type,
            $model->extension,
            $owners,
        );
    }

    // TODO: this should be changed to fromMedia when we remove asset library
//    public static function fromAsset(File $field, Asset $asset): static
//    {
//        $url = $field->isStoredOnPublicDisk()
//            ? $asset->url()
//            : ($field->generatesCustomUrl() ? $field->generateCustomUrl($asset, $field->getModel()) : '');
//
//        $thumbUrl = $field->isStoredOnPublicDisk()
//            ? $asset->url('thumb')
//            : '';
//
//        // If the conversions haven't run yet, we'll use the original image until they are uploaded
//        if ($field->isStoredOnPublicDisk() && ! $asset->getFirstMedia()?->hasGeneratedConversion('thumb')) {
//            $thumbUrl = $asset->url();
//        }
//
//        return new static(
//            $asset->id,
//            $asset->id,
//            $thumbUrl,
//            ('image' == $asset->getExtensionType()),
//            null,
//            $asset->filename(),
//            $asset->getSize(),
//            $asset->getMimeType(),
//            false,
////            ('image' == $asset->getExtensionType()),
////            $asset->getExtensionType(),
//        );
//    }

    public function toLivewire()
    {
        return [
            'mediaId' => $this->mediaId,
            'urls' => $this->urls,
            'filename' => $this->filename,
            'alt' => $this->alt,
            'size' => $this->size,
            'humanReadableSize' => $this->humanReadableSize,
            'mimeType' => $this->mimeType,
            'extension' => $this->extension,
            'owners' => $this->owners,
        ];
    }

    public static function fromLivewire($value)
    {
        return static::fromArray($value);
    }

    public static function fromArray($value)
    {
        return new static(...$value);
    }
}
