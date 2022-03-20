<?php


namespace Thinktomorrow\Chief\Tests\Shared;

use Illuminate\Testing\TestResponse;

trait UploadsFile
{
    private function uploadFile(string $fieldkey, $payload): TestResponse
    {
        $model = $this->page ?? $this->model;
        return $this->asAdmin()->put($this->manager($model)->route('update', $model), [
            'files' => [
                $fieldkey => $payload,
            ],
            'title' => 'title value',
            'custom' => 'custom value',
            'trans' => [
                'nl' => ['content_trans' => 'content nl'],
                'en' => ['content_trans' => 'content en'],
            ],
        ]);
    }

    private function uploadFileOrder(string $fieldkey, $payload): TestResponse
    {
        return $this->asAdmin()->put($this->manager->route('update', $this->page), [
            'filesOrder' => $payload,
            'title' => 'title value',
            'custom' => 'xxx',
            'trans' => [
                'nl' => ['content_trans' => 'xxx'],
                'en' => ['content_trans' => 'yyy'],
            ],
        ]);
    }
}
