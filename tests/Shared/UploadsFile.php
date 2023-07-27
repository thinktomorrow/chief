<?php


namespace Thinktomorrow\Chief\Tests\Shared;

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
}
