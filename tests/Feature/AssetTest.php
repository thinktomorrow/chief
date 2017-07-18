<?php

namespace Tests\Feature;

use Chief\Models\Article;
use Chief\Models\Asset;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AssetTest extends TestCase
{

    use DatabaseMigrations, DatabaseTransactions;

    public function tearDown()
    {
        Artisan::call('medialibrary:clear');
    }

    /**
     * @test
     */
    public function it_can_upload_an_image()
    {
        //upload a single image
        $asset = Asset::upload(UploadedFile::fake()->image('image.png'));

        $this->assertEquals($asset->getFilename(), 'image.png');
        $this->assertEquals($asset->getPath(), '/media/1/image.png');

        //upload a single image
        $asset = Asset::upload(UploadedFile::fake()->image('image2.png'));

        $this->assertEquals($asset->getFilename(), 'image2.png');
        $this->assertEquals($asset->getPath(), '/media/2/image2.png');
    }

    /**
     * @test
     */
    public function it_returns_null_when_uploading_an_invalid_file()
    {
        //upload a single image
        $asset = Asset::upload('image');

        $this->assertNull($asset);
    }

    /**
     * @test
     */
    public function it_can_upload_an_image_to_a_model()
    {
        $article = factory(Article::class)->create();

        //upload a single image
        $asset = Asset::upload(UploadedFile::fake()->image('image.png'))->attachToModel($article);

        $this->assertEquals($asset->getFilename(), 'image.png');
        $this->assertEquals($asset->getPath(), '/media/1/image.png');
        $this->assertEquals($article->asset()->first()->getFilename(), $asset->getFilename());

        //upload a single image
        $asset = Asset::upload(UploadedFile::fake()->image('image.png'));

        $this->assertEquals($asset->getFilename(), 'image.png');
        $this->assertEquals($asset->getPath(), '/media/2/image.png');
    }

    /**
     * @test
     */
    public function it_can_get_all_the_media_files()
    {
        //upload a single image
        $asset = Asset::upload(UploadedFile::fake()->image('image.png'));

        $this->assertEquals($asset->getFilename(), 'image.png');
        $this->assertEquals($asset->getPath(), '/media/1/image.png');

        //upload a single image
        $asset = Asset::upload(UploadedFile::fake()->image('image2.png'));

        $this->assertEquals($asset->getFilename(), 'image2.png');
        $this->assertEquals($asset->getPath(), '/media/2/image2.png');

        $this->assertEquals(2, Asset::getAllMedia()->count());
    }

    /**
     * @test
     */
    public function it_can_remove_an_image()
    {
        //upload a single image
        $asset = Asset::upload(UploadedFile::fake()->image('image.png'));

        $this->assertEquals($asset->getFilename(), 'image.png');
        $this->assertEquals($asset->getPath(), '/media/1/image.png');

        $asset2 = Asset::upload(UploadedFile::fake()->image('image2.png'));

        $this->assertEquals($asset2->getFilename(), 'image2.png');
        $this->assertEquals($asset2->getPath(), '/media/2/image2.png');

        Asset::remove($asset->id);

        $this->assertEquals(1, Asset::getAllMedia()->count());
        $this->assertEquals($asset2->id, Asset::getAllMedia()->first()->id);
    }

    /**
     * @test
     */
    public function it_can_remove_multiple_images()
    {
        //upload a single image
        $asset = Asset::upload(UploadedFile::fake()->image('image.png'));

        $this->assertEquals($asset->getFilename(), 'image.png');
        $this->assertEquals($asset->getPath(), '/media/1/image.png');

        $asset2 = Asset::upload(UploadedFile::fake()->image('image2.png'));

        $this->assertEquals($asset2->getFilename(), 'image2.png');
        $this->assertEquals($asset2->getPath(), '/media/2/image2.png');

        Asset::remove([$asset->id, $asset2->id]);

        $this->assertEquals(0, Asset::getAllMedia()->count());
    }

    /**
     * @test
     */
    public function it_can_upload_multiple_images()
    {
        //upload multiple images
        $images = [UploadedFile::fake()->image('image.png'), UploadedFile::fake()->image('image2.png')];

        $asset = Asset::upload($images);

        $this->assertEquals($asset[0]->getFilename(), 'image.png');
        $this->assertEquals($asset[0]->getPath(), '/media/1/image.png');

        $this->assertEquals($asset[1]->getFilename(), 'image2.png');
        $this->assertEquals($asset[1]->getPath(), '/media/2/image2.png');
    }

    /**
    * @test
    */
    public function it_can_create_conversions()
    {
        $asset = Asset::upload(UploadedFile::fake()->image('image.png'));

        $this->assertEquals($asset->getFilename(), 'image.png');
        $this->assertEquals($asset->getPath(), '/media/1/image.png');
        $this->assertEquals($asset->getPathForSize('thumb'), '/media/1/conversions/thumb.png');
    }

//    /**
//    * @test
//    */
//    public function it_can_upload_images_for_different_locales()
//    {
//    }

//
//    /**
//     * @test
//     */
//    public function it_can_crop_an_image()
//    {
//        Asset::upload($request->file('image'))->crop(x,y,w,h);
//    }
}
