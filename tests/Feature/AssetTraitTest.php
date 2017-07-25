<?php

namespace Tests\Feature;

use Chief\Models\Article;
use Chief\Models\Asset;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AssetTraitTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    /**
     * @test
     */
    public function it_can_get_a_file_url_without_a_type()
    {
        $article = factory(Article::class)->create();

        Asset::upload(UploadedFile::fake()->image('image.png'))->attachToModel($article);

        $this->assertEquals('/media/1/image.png', $article->getFileUrl());
    }

    /**
     * @test
     */
    public function it_can_get_a_file_url_with_a_type()
    {
        $article = factory(Article::class)->create();

        $article = Asset::upload(UploadedFile::fake()->image('bannerImage.png'))->attachToModel($article, 'banner');
        $article = Asset::upload(UploadedFile::fake()->image('image.png'))->attachToModel($article);

        $this->assertEquals('/media/1/bannerImage.png', $article->getFileUrl('banner'));
        $this->assertEquals('/media/2/image.png', $article->getFileUrl());
    }

    /**
     * @test
     */
    public function it_can_get_a_file_url_with_a_type_and_size()
    {
        $article = factory(Article::class)->create();

        Asset::upload(UploadedFile::fake()->image('image.png'))->attachToModel($article, 'banner');

        $this->assertEquals('/media/1/conversions/thumb.png', $article->getFileUrl('banner', 'thumb'));
    }

    /**
     * @test
     */
    public function it_can_get_a_file_url_with_type_for_locale()
    {
        $article = factory(Article::class)->create();

        Asset::upload(UploadedFile::fake()->image('image.png'))->attachToModel($article, 'banner');
        $article->addFile(UploadedFile::fake()->image('imageFR.png'), 'banner', 'fr');

        $this->assertEquals('/media/1/image.png', $article->getFileUrl('banner', '', 'nl'));
        $this->assertEquals('/media/2/imageFR.png', $article->getFileUrl('banner', '', 'fr'));
    }

    /**
     * @test
     */
    public function it_can_get_a_file_url_with_all_variables()
    {
        $article = factory(Article::class)->create();

        Asset::upload(UploadedFile::fake()->image('image.png'))->attachToModel($article, 'banner', 'nl');
        $article->addFile(UploadedFile::fake()->image('imageFR.png'), 'thumbnail', 'fr');

        $this->assertEquals('/media/1/conversions/large.png', $article->getFileUrl('banner', 'large', 'nl'));
        $this->assertEquals('/media/2/conversions/thumb.png', $article->getFileUrl('thumbnail', 'thumb', 'fr'));
    }

    /**
     * @test
     */
    public function it_can_get_the_default_locale_if_the_translation_does_not_exist()
    {
        $article = factory(Article::class)->create();

        Asset::upload(UploadedFile::fake()->image('image.png'))->attachToModel($article, 'banner', 'nl');

        $this->assertEquals('/media/1/image.png', $article->getFileUrl('banner', '', 'nl'));
        $this->assertEquals('/media/1/image.png', $article->getFileUrl('banner', '', 'fr'));
    }

    /**
     * @test
     */
    public function it_can_check_if_it_has_a_file_without_a_type()
    {
        $article = factory(Article::class)->create();

        $this->assertFalse($article->hasFile());

        $article = Asset::upload(UploadedFile::fake()->image('image.png'))->attachToModel($article);

        $this->assertTrue($article->hasFile());
    }

    /**
     * @test
     */
    public function it_can_check_if_it_has_a_file_with_a_type()
    {
        $article = factory(Article::class)->create();

        $this->assertFalse($article->hasFile('banner'));

        Asset::upload(UploadedFile::fake()->image('image.png'))->attachToModel($article,'banner');

        $this->assertTrue($article->hasFile('banner'));
    }

    /**
     * @test
     */
    public function it_can_add_a_file_translation()
    {
        $article = factory(Article::class)->create();
        $article->addFile(UploadedFile::fake()->image('image.png'),'banner','nl');
        $article->addFile(UploadedFile::fake()->image('imagefr.png'),'banner','fr');

        $this->assertTrue($article->hasFile('banner'));
        $this->assertTrue($article->hasFile('banner', 'fr'));
        $this->assertFalse($article->hasFile('banner', 'en'));
    }

    /**
     * @test
     */
    public function it_can_add_a_file_translation_for_default_locale()
    {
        $article = factory(Article::class)->create();
        $article->addFile(UploadedFile::fake()->image('image.png'),'banner');
        $article->addFile(UploadedFile::fake()->image('imagefr.png'),'banner','fr');

        $this->assertTrue($article->hasFile('banner'));
        $this->assertTrue($article->hasFile('banner', 'fr'));

    }

    /**
     * @test
     */
    public function it_can_replace_a_translation()
    {
        $article = factory(Article::class)->create();
        $article->addFile(UploadedFile::fake()->image('image.png'), 'banner');
        $article->addFile(UploadedFile::fake()->image('imageNL.png'), 'banner');

        $this->assertEquals('/media/2/imageNL.png',$article->getFileUrl('banner'));

    }

    /**
     * @test
     */
    public function it_can_attach_an_asset_if_it_is_given_instead_of_a_file()
    {
        $article = factory(Article::class)->create();
        $asset = Asset::upload(UploadedFile::fake()->image('image.png', 100, 100));

        $article->addFile($asset);

        $this->assertEquals('/media/1/image.png', $article->getFileUrl());
    }

    /**
     * @test
    */
    public function it_can_attach_an_asset_to_multiple_models()
    {
        $article    = factory(Article::class)->create();
        $article2   = factory(Article::class)->create();
        $asset      = Asset::upload(UploadedFile::fake()->image('image.png', 100, 100));
        $asset->attachToModel($article, 'banner');

        $article2->addFile($asset, 'banner');

        $this->assertEquals('/media/1/conversions/thumb.png', $article->getFileUrl('banner', 'thumb'));
        $this->assertEquals('/media/1/conversions/thumb.png', $article2->getFileUrl('banner', 'thumb'));
    }

    /**
     * @test
     */
    public function it_can_change_an_image_connected_to_multiple_models_without_changing_the_other_models()
    {
        $article    = factory(Article::class)->create();
        $article2   = factory(Article::class)->create();
        $asset      = Asset::upload(UploadedFile::fake()->image('image.png', 100, 100));
        $asset->attachToModel($article, 'banner');

        $article2->addFile($asset, 'banner');
        $article->addFile(UploadedFile::fake()->image('image2.png', 100, 100), 'banner');

        $this->assertEquals('/media/2/image2.png', $article->getFileUrl('banner'));
        $this->assertEquals('/media/1/image.png', $article2->getFileUrl('banner'));
    }


}
