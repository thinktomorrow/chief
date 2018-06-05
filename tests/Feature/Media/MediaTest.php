<?php

namespace Thinktomorrow\Chief\Tests\Feature\Pages;

use Illuminate\Http\UploadedFile;
use Thinktomorrow\Chief\Media\MediaType;
use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Tests\TestCase;

class MediaTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->app['config']->set('thinktomorrow.chief.collections', [
            'statics' => Page::class,
            'articles' => ArticleFake::class,
        ]);
    }
    
    /** @test */
    function it_can_have_an_image()
    {
        $fake = ArticleFake::create([]);

        $fake->addFile(UploadedFile::fake()->image('image.png'), 'images');

        $this->assertCount(1, $fake->assets);
    }

    /** @test */
    function a_page_can_have_an_image_for_hero()
    {
        $page = Page::create(['collection' => 'statics']);

        $page->addFile(UploadedFile::fake()->image('image.png'), MediaType::HERO);

        $this->assertTrue($page->hasFile(MediaType::HERO));
        $this->assertCount(1, $page->getAllFiles(MediaType::HERO));
    }

    /** @test */
    function it_can_add_image_via_wysiwyg_editor()
    {
        $this->setUpDefaultAuthorization();

        $article = ArticleFake::create(['collection' => 'articles']);

        $response = $this->asAdmin()->post(route('media.upload'),[
            'model_type' => ArticleFake::class,
            'model_id' => $article->id,
            'file' => [
                UploadedFile::fake()->image('image.png')
            ],
        ]);

        $this->assertTrue($article->hasFile(MediaType::CONTENT));
        $this->assertCount(1, $article->getAllFiles(MediaType::CONTENT));

        $response->assertStatus(201)
                 ->assertJson([
                        "file" => [
                            "url" => $article->getFileUrl(MediaType::CONTENT),
                            "id" => $article->getAllFiles(MediaType::CONTENT)->first()->id,
                        ]
                 ]);
    }
}

class ArticleFake extends Page{}
