<?php

namespace Thinktomorrow\Chief\Tests\Feature\Pages\Media;

use Illuminate\Http\UploadedFile;
use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Modules\Module;
use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\Media\MediaType;
use Thinktomorrow\AssetLibrary\Application\AddAsset;
use Thinktomorrow\Chief\Tests\Fakes\ArticlePageFake;

class MediaTest extends TestCase
{
    /** @test */
    public function it_can_have_an_image()
    {
        $fake = ArticlePageFake::create([]);

        app(AddAsset::class)->add($fake, UploadedFile::fake()->image('image.png'), 'images', 'nl');

        $this->assertCount(1, $fake->assets());
    }

    /** @test */
    public function a_page_can_have_an_image_for_hero()
    {
        $page = Page::create(['morph_key' => 'singles']);

        app(AddAsset::class)->add($page, UploadedFile::fake()->image('image.png'), MediaType::HERO, 'nl');

        $this->assertCount(1, $page->assets(MediaType::HERO));
    }

    /** @test */
    public function it_can_add_image_via_wysiwyg_editor()
    {
        $this->disableExceptionHandling();
        $this->setUpDefaultAuthorization();

        $article = ArticlePageFake::create();

        $response = $this->asAdmin()->post(route('pages.media.upload', $article->id), [
            'file' => [
                UploadedFile::fake()->image('image.png')
            ],
        ]);

        $assets = $article->assets(MediaType::CONTENT, 'nl');
        $this->assertCount(1, $assets);

        $response->assertStatus(201)
                 ->assertJson([
                        "file-".$assets->first()->id => [
                            "url" => $article->asset(MediaType::CONTENT)->url(),
                            "id" => $assets->first()->id,
                        ]
                 ]);
    }
}
