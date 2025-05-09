<?php

namespace Thinktomorrow\Chief\Assets\Tests\TestSupport;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\TestResponse;
use Livewire\Features\SupportFileUploads\FileUploadConfiguration;
use Livewire\Features\SupportFileUploads\FileUploadController;
use Thinktomorrow\AssetLibrary\HasAsset;
use Thinktomorrow\Chief\Forms\App\Actions\SaveFileField;
use Thinktomorrow\Chief\Forms\Tests\TestSupport\PageWithAssets;
use Thinktomorrow\Chief\Fragments\Fragment;
use Thinktomorrow\Chief\Managers\Register\Registry;

trait TestingFileUploads
{
    // Replace with updateForm() or updateFragment() helpers...
    private function uploadFile($model, $payload): TestResponse
    {
        return $this->asAdmin()->put($this->manager($model)->route('update', $model), [
            'files' => $payload,
            'title' => [
                'nl' => 'titel',
                'en' => 'title',
            ],
            'custom' => 'custom value',
            'content_trans' => [
                'nl' => 'content nl',
                'en' => 'content en',
            ],
        ]);
    }

    private function uploadImageField(HasAsset $model, string $fieldKey = 'image', string $path = 'test/image.png', array $payload = []): void
    {
        $basepath = substr($path, 0, strrpos($path, '/'));
        $filename = substr($path, strrpos($path, '/') + 1);

        $payload['path'] = Storage::path($path);
        $payload['originalName'] = $filename;

        $this->storeFakeImageOnDisk($filename, $basepath);

        $this->saveFileField($model, $fieldKey, [
            'nl' => [
                'uploads' => [
                    $this->fileFormPayload($payload),
                ],
            ],
        ]);
    }

    private function attachFile(HasAsset $model, $fieldKey, int $assetId)
    {
        return $this->saveFileField($model, $fieldKey, [
            'nl' => [
                'attach' => [
                    ['id' => $assetId],
                ],
            ],
        ]);
    }

    private function saveFileField(HasAsset $model, $fieldKey, array $payload): array
    {
        if ($model instanceof Fragment) {
            $resource = $model;
            $model = $model->getFragmentModel();
        } else {
            $resource = app(Registry::class)->findResourceByModel($model::class);
        }

        return app(SaveFileField::class)->handle(
            $model,
            $resource->field($model, $fieldKey),
            [
                'files' => [
                    $fieldKey => $payload,
                ],
            ],
        );
    }

    private function storeFakeImageOnDisk(string $filename, ?string $basepath = null, $width = 10, $height = 20, string $disk = 'local'): UploadedFile
    {
        $uploadedFile = UploadedFile::fake()->image($filename, $width, $height);

        // Store the file manually
        $realPath = $uploadedFile->storeAs($basepath ?: '/', $filename, $disk);

        $fullPath = Storage::disk($disk)->path($realPath);

        return new \Illuminate\Http\UploadedFile(
            $fullPath,
            $filename,
            null,
            null,
            true // Set test mode to true
        );
    }

    protected function storeFakeImageOnLivewireDisk(string $filename, $width = 10, $height = 20)
    {
        $uploadedFile = UploadedFile::fake()->image($filename, $width, $height);

        Storage::fake(FileUploadConfiguration::disk());

        $paths = app(FileUploadController::class)->validateAndStore([
            $uploadedFile,
        ], FileUploadConfiguration::disk());

        return ltrim($paths[0], '/');
    }

    private function fileFormPayload(array $values = []): array
    {
        return array_merge([
            'path' => Storage::path('image-temp-name.png'),
            'originalName' => 'image.png',
            'mimeType' => 'image/png',
            'fieldValues' => [],
        ], $values);
    }

    private function createExistingImage()
    {
        PageWithAssets::migrateUp();
        chiefRegister()->resource(PageWithAssets::class);

        $model = PageWithAssets::create();
        $this->storeFakeImageOnDisk('image-temp-name.png');
        $this->saveFileField($model, 'thumb', [
            'nl' => [
                'uploads' => [
                    $this->fileFormPayload(),
                ],
            ],
        ]);
    }
}
