<?php

namespace App;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Thinktomorrow\AssetLibrary\Asset;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

class StoreFilesTest extends ChiefTestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function tearDown(): void
    {
        Storage::delete('test/image-temp-name.png');

        parent::tearDown();
    }

    public function test_it_can_store_uploads()
    {
        UploadedFile::fake()->image('image.png')->storeAs('test', 'image-temp-name.png');

        $this->storeFiles([
            [
                'id' => 'xxx',
                'path' => Storage::path('test/image-temp-name.png'),
                'originalName' => 'image.png',
                'mimeType' => 'image/png',
            ],
        ]);

        $this->assertCount(1, Asset::all());
        $this->assertEquals('image.png', Asset::first()->getFileName());
    }
}
