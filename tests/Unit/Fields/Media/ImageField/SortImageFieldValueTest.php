<?php

namespace Thinktomorrow\Chief\Tests\Unit\Fields\Media\ImageField;

use Illuminate\Http\UploadedFile;
use Thinktomorrow\AssetLibrary\Application\AddAsset;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\PageFormParams;
use Thinktomorrow\Chief\Tests\Shared\UploadsFile;

class SortImageFieldValueTest extends ChiefTestCase
{
    use PageFormParams;
    use UploadsFile;

    private $page;
    private $manager;

    protected function setUp(): void
    {
        parent::setUp();

        $this->page = $this->setupAndCreateArticle();
        $this->manager = $this->manager($this->page);
    }

    /** @test */
    public function assets_can_be_sorted()
    {
        app(AddAsset::class)->add($this->page, UploadedFile::fake()->image('image.png'), 'thumb_image_trans', 'nl');
        app(AddAsset::class)->add($this->page, UploadedFile::fake()->image('image2.png'), 'thumb_image_trans', 'nl');

        $images = $this->page->fresh()->assets('thumb_image_trans');

        $this->uploadFileOrder('thumb_image_trans', [
            'nl' => [
                'thumb_image_trans' => $images->last()->id . ',' . $images->first()->id,
            ],
        ]);

        $assetIds = $this->page->fresh()->assets('thumb_image_trans')->pluck('id')->toArray();

        $this->assertEquals([$images->last()->id, $images->first()->id], $assetIds);
    }

    /** @test */
    public function localized_assets_can_be_sorted()
    {
        app(AddAsset::class)->add($this->page, UploadedFile::fake()->image('image.png'), 'thumb_image_trans', 'nl');
        app(AddAsset::class)->add($this->page, UploadedFile::fake()->image('image2.png'), 'thumb_image_trans', 'nl');
        app(AddAsset::class)->add($this->page, UploadedFile::fake()->image('image3.png'), 'thumb_image_trans', 'en');
        app(AddAsset::class)->add($this->page, UploadedFile::fake()->image('image4.png'), 'thumb_image_trans', 'en');

        $nl_images = $this->page->assets('thumb_image_trans', 'nl');
        $en_images = $this->page->assets('thumb_image_trans', 'en');

        $this->uploadFileOrder('thumb_image_trans', [
            'nl' => [
                'files-' . 'thumb_image_trans' => $nl_images[1]->id . ',' . $nl_images[0]->id,
            ],
            'en' => [
                'files-' . 'thumb_image_trans' => $en_images[3]->id . ',' . $en_images[2]->id,
            ],
        ]);

        $nl_newImagesSorted = $this->page->refresh()->assets('thumb_image_trans', 'nl')->pluck('id')->toArray();
        $en_newImagesSorted = $this->page->assets('thumb_image_trans', 'en')->pluck('id')->toArray();

        $this->assertEquals([$nl_images[1]->id,$nl_images[0]->id], $nl_newImagesSorted);
        $this->assertEquals([$en_images[3]->id, $en_images[2]->id], $en_newImagesSorted);
    }
}
