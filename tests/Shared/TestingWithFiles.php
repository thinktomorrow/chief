<?php

namespace Thinktomorrow\Chief\Tests\Shared;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\FileUploadController;
use Thinktomorrow\AssetLibrary\HasAsset;
use Thinktomorrow\Chief\Assets\App\StoreFiles;
use Thinktomorrow\Chief\Forms\App\Actions\SaveFileField;
use Thinktomorrow\Chief\Resource\Resource;

trait TestingWithFiles
{
    protected function saveFileField(Resource $resource, HasAsset $model, $fieldKey, array $payload)
    {
        app(SaveFileField::class)->handle(
            $model,
            $resource->field($model, $fieldKey),
            [
                'files' => [
                    $fieldKey => $payload,
                ],
            ],
        );
    }

    /** Store file directly to media-gallery */
    protected function storeFiles(array $payload)
    {
        return app(StoreFiles::class)->handle(
            [
                'uploads' => $payload,
            ],
        );
    }

    protected function uploadForLivewire(UploadedFile $file)
    {
        Storage::fake('tmp-for-tests');

        $paths = app(FileUploadController::class)->validateAndStore([
            $file,
        ], 'tmp-for-tests');

        return ltrim($paths[0], '/');
    }

    protected function dummyBase64Payload()
    {
        return ';base64,iVBORw0KGgoAAAANSUhEUgAAA/gAAAE4AQMAAADVYspJAAAAA1BMVEUEAgSVKDOdAAAAPUlEQVR42u3BAQ0AAADCoPdPbQ8HFAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA/BicAAABWZX81AAAAABJRU5ErkJggg==';
    }

    protected function dummyUploadedFile($name = 'tt-document.pdf', $sizeInKilobytes = 100)
    {
        return UploadedFile::fake()->create($name, $sizeInKilobytes);
    }
}
