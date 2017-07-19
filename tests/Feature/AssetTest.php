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
     * @group testing
     * @test
     */
    public function it_can_upload_an_image()
    {
        //upload a single image
        $asset = Asset::upload(UploadedFile::fake()->image('image.png'));

        $this->assertEquals($asset->getFilename(), 'image.png');
        $this->assertEquals($asset->getImageUrl(), '/media/1/image.png');

        //upload a single image
        $asset = Asset::upload(UploadedFile::fake()->image('image2.png'));

        $this->assertEquals($asset->getFilename(), 'image2.png');
        $this->assertEquals($asset->getImageUrl(), '/media/2/image2.png');
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
        $this->assertEquals($asset->getImageUrl(), '/media/1/image.png');
        $this->assertEquals($article->asset()->first()->getFilename(), $asset->getFilename());

        //upload a single image
        $asset = Asset::upload(UploadedFile::fake()->image('image.png'));

        $this->assertEquals($asset->getFilename(), 'image.png');
        $this->assertEquals($asset->getImageUrl(), '/media/2/image.png');
    }

    /**
     * @test
     */
    public function it_can_get_all_the_media_files()
    {
        //upload a single image
        $asset = Asset::upload(UploadedFile::fake()->image('image.png'));

        $this->assertEquals($asset->getFilename(), 'image.png');
        $this->assertEquals($asset->getImageUrl(), '/media/1/image.png');

        //upload a single image
        $asset = Asset::upload(UploadedFile::fake()->image('image2.png'));

        $this->assertEquals($asset->getFilename(), 'image2.png');
        $this->assertEquals($asset->getImageUrl(), '/media/2/image2.png');

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
        $this->assertEquals($asset->getImageUrl(), '/media/1/image.png');

        $asset2 = Asset::upload(UploadedFile::fake()->image('image2.png'));

        $this->assertEquals($asset2->getFilename(), 'image2.png');
        $this->assertEquals($asset2->getImageUrl(), '/media/2/image2.png');

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
        $this->assertEquals($asset->getImageUrl(), '/media/1/image.png');

        $asset2 = Asset::upload(UploadedFile::fake()->image('image2.png'));

        $this->assertEquals($asset2->getFilename(), 'image2.png');
        $this->assertEquals($asset2->getImageUrl(), '/media/2/image2.png');

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
        $this->assertEquals($asset[0]->getImageUrl(), '/media/1/image.png');

        $this->assertEquals($asset[1]->getFilename(), 'image2.png');
        $this->assertEquals($asset[1]->getImageUrl(), '/media/2/image2.png');
    }

    /**
    * @test
    */
    public function it_can_create_conversions()
    {
        $asset = Asset::upload(UploadedFile::fake()->image('image.png'));

        $this->assertEquals($asset->getFilename(), 'image.png');
        $this->assertEquals($asset->getImageUrl(), '/media/1/image.png');
        $this->assertEquals($asset->getPathForSize('thumb'), '/media/1/conversions/thumb.png');
    }

    /**
    * @test
    */
    public function it_can_return_the_url_for_pdf_or_xls()
    {
        $images = [UploadedFile::fake()->create('foobar.pdf'), UploadedFile::fake()->create('foobar.xls')];

        $asset = Asset::upload($images);

        $this->assertEquals($asset[0]->getFilename(), 'foobar.pdf');
        $this->assertEquals($asset[0]->getPath(), '/media/1/foobar.pdf');

        $this->assertEquals($asset[1]->getFilename(), 'foobar.xls');
        $this->assertEquals($asset[1]->getPath(), '/media/2/foobar.xls');
    }

    /**
    * @test
    */
    public function it_can_get_the_image_url()
    {
        $files = [UploadedFile::fake()->create('foobar.pdf'), UploadedFile::fake()->create('foobar.xls'), UploadedFile::fake()->image('image.mp4')];

        $asset = Asset::upload($files);

        $this->assertEquals($asset[0]->getFilename(), 'foobar.pdf');
        $this->assertEquals($asset[0]->getImageUrl(), '../assets/back/img/pdf.png');

        $this->assertEquals($asset[1]->getFilename(), 'foobar.xls');
        $this->assertEquals($asset[1]->getImageUrl(), '../assets/back/img/xls.png');

        $this->assertEquals($asset[2]->getFilename(), 'image.mp4');
        $this->assertEquals($asset[2]->getImageUrl(), '../assets/back/img/other.png');
    }

    /**
    * @test
    */
    public function it_can_get_its_mimetype()
    {
        $asset = Asset::upload(UploadedFile::fake()->image('image.png'));

        $this->assertEquals($asset->getMimeType(), 'image/png');
    }

    /**
    * @test
    */
    public function it_can_get_its_size()
    {
        $asset = Asset::upload(UploadedFile::fake()->image('image.png'));

        $this->assertEquals($asset->getSize(), '70 B');
    }

    /**
     * @test
     */
    public function it_can_get_its_dimensions()
    {
        $asset = Asset::upload(UploadedFile::fake()->image('image.png', 100, 100));

        $this->assertEquals($asset->getDimensions(), '100 x 100');
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
