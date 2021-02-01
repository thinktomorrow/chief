<?php


namespace Thinktomorrow\Chief\Tests\Shared;


use Illuminate\Testing\TestResponse;

trait UploadsFile
{
    private function uploadFile(string $fieldkey, $payload): TestResponse
    {
        return $this->asAdmin()->put($this->manager->route('update', $this->page), [
            'files' => [
                $fieldkey => $payload,
            ],
            'custom' => 'xxx',
            'trans' => [
                'nl' => ['content_trans' => 'xxx'],
                'en' => ['content_trans' => 'yyy'],
            ],
        ]);
    }

    private function uploadImage(string $fieldkey, $payload): TestResponse
    {
        return $this->asAdmin()->put($this->manager->route('update', $this->page), [
            'images' => [
                $fieldkey => $payload,
            ],
            'custom' => 'xxx',
            'trans' => [
                'nl' => ['content_trans' => 'xxx'],
                'en' => ['content_trans' => 'yyy'],
            ],
        ]);
    }

    private function uploadFileOrder(string $fieldkey, $payload): TestResponse
    {
        return $this->asAdmin()->put($this->manager->route('update', $this->page), [
            'filesOrder' => $payload,
            'custom' => 'xxx',
            'trans' => [
                'nl' => ['content_trans' => 'xxx'],
                'en' => ['content_trans' => 'yyy'],
            ],
        ]);
    }
}
