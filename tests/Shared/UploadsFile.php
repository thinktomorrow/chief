<?php

namespace Thinktomorrow\Chief\Tests\Shared;

use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\TestResponse;

trait UploadsFile
{
    private function uploadFile($model, $payload): TestResponse
    {
        return $this->asAdmin()->put($this->manager($model)->route('update', $model), [
            'files' => $payload,
            'title' => 'title value',
            'custom' => 'custom value',
            'trans' => [
                'nl' => ['content_trans' => 'content nl'],
                'en' => ['content_trans' => 'content en'],
            ],
        ]);
    }

    private function uploadAsset(string $path, string $filename, string $mimeType, $model, $resource): void
    {
        $this->saveFileField($resource, $model, 'thumb', [
            'nl' => [
                'uploads' => [
                    [
                        'id' => 'xxx',
                        'path' => Storage::path($path),
                        'originalName' => $filename,
                        'mimeType' => $mimeType,
                    ],
                ],
            ],
        ]);
    }
}
