<?php

namespace Thinktomorrow\Chief\Tests\Feature\Media\Gallery;

use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\AssetLibrary\Application\AssetUploader;

class MediaApiTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpDefaultAuthorization();

    }

    /** @test */
    public function it_can_get_a_json_with_assets()
    {
        $asset = AssetUploader::upload(UploadedFile::fake()->image('image.png'));

        $response = $this->asAdmin()->get(route('chief.api.media'));
        $response->assertStatus(200)
                 ->assertJson([
                        [
                            "id"         => $asset->id,
                            "url"        => $asset->url(),
                            "filename"   => $asset->filename(),
                            "dimensions" => $asset->getDimensions(),
                            "size"       => $asset->getSize()
                        ]
                 ]);
    }

    /** @test */
    public function it_can_limit_assets_fetched()
    {
        AssetUploader::upload(UploadedFile::fake()->image('image.png'));
        AssetUploader::upload(UploadedFile::fake()->image('image.png'));

        $response = $this->asAdmin()->get(route('chief.api.media'). "?limit=1");

        $response->assertStatus(200)
                 ->assertJsonCount(1);

        $response = $this->asAdmin()->get(route('chief.api.media'). "?limit=2");

        $response->assertStatus(200)
                 ->assertJsonCount(2);
    }

    /** @test */
    public function it_can_paginate_with_limit_and_offset()
    {
        $asset = AssetUploader::upload(UploadedFile::fake()->image('image.png'));
        $asset2 = AssetUploader::upload(UploadedFile::fake()->image('image.png'));

        $asset->created_at = Carbon::now()->addHour();
        $asset->save();

        $response = $this->asAdmin()->get(route('chief.api.media'). "?limit=1");

        $response->assertStatus(200)
                 ->assertJson([[
                    "id"         => $asset->id,
                    "url"        => $asset->url(),
                    "filename"   => $asset->filename(),
                    "dimensions" => $asset->getDimensions(),
                    "size"       => $asset->getSize()
                ]]);

        $response = $this->asAdmin()->get(route('chief.api.media'). "?limit=1&offset=1");

        $response->assertStatus(200)
                 ->assertJson([[
                    "id"         => $asset2->id,
                    "url"        => $asset2->url(),
                    "filename"   => $asset2->filename(),
                    "dimensions" => $asset2->getDimensions(),
                    "size"       => $asset2->getSize()
                ]]);
    }

    /** @test */
    public function it_fetches_the_most_recently_added_asset_first()
    {
        $asset = AssetUploader::upload(UploadedFile::fake()->image('image.png'));
        $asset2 = AssetUploader::upload(UploadedFile::fake()->image('image.png'));

        $asset2->created_at = Carbon::now()->addHour();
        $asset2->save();

        $response = $this->asAdmin()->get(route('chief.api.media'). "?limit=1");

        $response->assertStatus(200)
                 ->assertJson([[
                    "id"         => $asset2->id,
                    "url"        => $asset2->url(),
                    "filename"   => $asset2->filename(),
                    "dimensions" => $asset2->getDimensions(),
                    "size"       => $asset2->getSize()
                 ]]);
    }
}
