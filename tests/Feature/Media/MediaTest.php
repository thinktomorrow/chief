<?php

namespace Thinktomorrow\Chief\Tests\Feature\Pages\Media;

use Illuminate\Http\UploadedFile;
use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Modules\Module;
use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\Media\MediaType;
use Thinktomorrow\Chief\Tests\Fakes\ArticlePageFake;

class MediaTest extends TestCase
{
    /** @test */
    public function it_can_have_an_image()
    {
        $fake = ArticlePageFake::create([]);

        $fake->addFile(UploadedFile::fake()->image('image.png'), 'images');

        $this->assertCount(1, $fake->assets);
    }

    /** @test */
    public function a_page_can_have_an_image_for_hero()
    {
        $page = Page::create(['morph_key' => 'singles']);

        $page->addFile(UploadedFile::fake()->image('image.png'), MediaType::HERO);

        $this->assertTrue($page->hasFile(MediaType::HERO));
        $this->assertCount(1, $page->getAllFiles(MediaType::HERO));
    }

   

    /** @test */
    public function it_can_add_image_via_wysiwyg_editor()
    {
        $this->setUpDefaultAuthorization();

        $article = ArticlePageFake::create();

        $response = $this->asAdmin()->post(route('pages.media.upload', $article->id), [
            'file' => [
                UploadedFile::fake()->image('image.png')
            ],
        ]);
        $this->assertTrue($article->hasFile(MediaType::CONTENT));

        $assets = $article->getAllFiles(MediaType::CONTENT, 'nl');
        $this->assertCount(1, $assets);

        $response->assertStatus(201)
                 ->assertJson([
                        "file-".$assets->first()->id => [
                            "url" => $article->getFileUrl(MediaType::CONTENT),
                            "id" => $assets->first()->id,
                        ]
                 ]);
    }
}
