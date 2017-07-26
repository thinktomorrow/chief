<?php

namespace Tests\Feature;

use Chief\Models\Article;
use Chief\Models\Asset;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Artisan;
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
        $this->assertEquals('image.png', $asset->getFilename());
        $this->assertEquals( '/media/1/image.png', $asset->getImageUrl());

        //upload a single image
        $asset = Asset::upload(UploadedFile::fake()->image('image2.png'));

        $this->assertEquals( 'image2.png', $asset->getFilename());
        $this->assertEquals( '/media/2/image2.png', $asset->getImageUrl());
    }

    /**
     * @test
     */
    public function it_returns_null_when_uploading_an_invalid_file()
    {
        //upload a single image
        $asset = Asset::upload(5);

        $this->assertNull($asset);
    }

    /**
     * @test
     */
    public function it_can_upload_an_image_to_a_model()
    {
        $original = factory(Article::class)->create();

        //upload a single image
        $article = Asset::upload(UploadedFile::fake()->image('image.png'))->attachToModel($original);

        $this->assertEquals( 'image.png', $article->getFilename());
        $this->assertEquals( '/media/1/image.png', $article->getFileUrl());
        $this->assertEquals($original->assets()->first()->getFilename(), $article->getFilename());

        //upload a single image
        $asset = Asset::upload(UploadedFile::fake()->image('image.png'));

        $this->assertEquals( 'image.png', $asset->getFilename());
        $this->assertEquals( '/media/2/image.png', $asset->getImageUrl());
    }

    /**
     * @test
     */
    public function it_can_get_all_the_media_files()
    {
        //upload a single image
        $asset = Asset::upload(UploadedFile::fake()->image('image.png'));

        $this->assertEquals('image.png', $asset->getFilename());
        $this->assertEquals('/media/1/image.png', $asset->getFileUrl());

        $article = factory(Article::class)->create();

        //upload a single image
        $article = Asset::upload(UploadedFile::fake()->image('image2.png'))->attachToModel($article, 'banner',  'nl');

        $this->assertEquals('image2.png', $article->getFilename('banner', 'nl'));
        $this->assertEquals('/media/2/image2.png', $article->getFileUrl('banner', '',  'nl'));

        $article->addFile(UploadedFile::fake()->image('image3.png'), 'thumbnail', 'fr');

        $this->assertEquals('image3.png', $article->getFilename('thumbnail',  'fr'));
        $this->assertEquals('/media/3/image3.png', $article->getFileUrl('thumbnail', '', 'fr'));

        $this->assertEquals(3, Asset::getAllAssets()->count());
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

        $this->assertEquals(1, Asset::getAllAssets()->count());
        $this->assertEquals($asset2->id, Asset::getAllAssets()->first()->id);
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

        $this->assertEquals(0, Asset::getAllAssets()->count());
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
        $this->assertEquals('/media/1/conversions/thumb.png', $asset->getFileUrl('thumb'));
    }

    /**
    * @test
    */
    public function it_can_return_the_url_for_pdf_or_xls()
    {
        $images = [UploadedFile::fake()->create('foobar.pdf'), UploadedFile::fake()->create('foobar.xls')];

        $asset = Asset::upload($images);

        $this->assertEquals($asset[0]->getFilename(), 'foobar.pdf');
        $this->assertEquals($asset[0]->getFileUrl(), '/media/1/foobar.pdf');

        $this->assertEquals($asset[1]->getFilename(), 'foobar.xls');
        $this->assertEquals($asset[1]->getFileUrl(), '/media/2/foobar.xls');
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

    /**
    * @test
    */
    public function it_can_upload_images_for_different_locales()
    {
        $asset = Asset::upload(UploadedFile::fake()->image('image.png'), 'nl');
        $this->assertEquals('image.png', $asset->getFilename('','nl'));
    }

    /**
     * @test
     */
    public function it_can_return_a_type_field_for_uploads()
    {
        $this->assertEquals('<input type="hidden" value="foo" name="type">', Asset::typeField('foo'));
        $this->assertEquals('<input type="hidden" value="bar" name="type">', Asset::typeField('bar'));
    }

    /**
     * @test
     */
    public function it_can_return_a_locale_field_for_uploads()
    {
        $this->assertEquals('<input type="hidden" value="nl" name="locale">', Asset::localeField('nl'));
        $this->assertEquals('<input type="hidden" value="fr" name="locale">', Asset::localeField('fr'));
    }

    /**
     * @test
     */
    public function it_can_check_if_it_has_a_file()
    {
        $asset = Asset::upload(UploadedFile::fake()->image('image.png', 100, 100));

        $this->assertTrue($asset->hasFile());
    }

    /**
     * @test
     */
    public function it_returns_an_empty_string_if_there_is_no_media()
    {
        $asset = new Asset;

        $this->assertEquals('', $asset->getMimeType());
        $this->assertEquals('', $asset->getSize());
        $this->assertEquals('', $asset->getDimensions());
        $this->assertEquals('../assets/back/img/other.png', $asset->getImageUrl());
        $this->assertEquals('../assets/back/img/other.png', $asset->getFileUrl());
    }

    /**
     * @test
     */
    public function it_can_attach_an_asset_instead_of_a_file()
    {
        $asset = Asset::upload(UploadedFile::fake()->image('image.png'));

        $asset2 = Asset::upload($asset);

        $this->assertEquals( '/media/1/image.png', $asset2->getFileUrl());
    }

    /**
     * @test
     */
    public function it_can_attach_an_asset_to_multiple_models()
    {
        $asset = Asset::upload(UploadedFile::fake()->image('image.png'));

        $asset2 = Asset::upload($asset);

        $this->assertEquals( '/media/1/image.png', $asset->getFileUrl());
        $this->assertEquals( '/media/1/image.png', $asset2->getFileUrl());
    }

    /**
     * @group testing
     * @test
     */
    public function it_can_get_the_extensions_for_filtering()
    {
        //TODO uncomment these when we can supply the mimetype to the uploaded file
        $asset  =  Asset::upload(UploadedFile::fake()->image('image.png'));
//        $asset1 =  Asset::upload(UploadedFile::fake()->create('image.pdf'));
//        $asset2 =  Asset::upload(UploadedFile::fake()->create('image.xls'));
        $asset3 =  Asset::upload(UploadedFile::fake()->create('image.test'));


        $this->assertEquals('image', $asset->getExtensionForFilter());
//        $this->assertEquals('pdf', $asset1->getExtensionForFilter());
//        $this->assertEquals('excel', $asset2->getExtensionForFilter());
        $this->assertEquals('', $asset3->getExtensionForFilter());
    }

    /**
     * @test
     */
    public function it_can_get_the_typefield_with_locale()
    {
        $this->assertEquals('<input type="hidden" value="foo" name="type">', Asset::typeField('foo'));
        $this->assertEquals('<input type="hidden" value="bar" name="trans[fr][files][]">', Asset::typeField('bar', 'fr'));
    }

//
//    /**
//     * @test
//     */
//    public function it_can_crop_an_image()
//    {
//        Asset::upload($request->file('image'))->crop(x,y,w,h);
//    }
}
