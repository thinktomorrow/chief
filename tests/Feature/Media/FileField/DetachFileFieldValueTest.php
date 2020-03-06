<?php

namespace Thinktomorrow\Chief\Tests\Feature\Media\FileField;

use Illuminate\Http\UploadedFile;
use Thinktomorrow\AssetLibrary\Asset;
use Thinktomorrow\Chief\Pages\Single;
use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\Management\Register;
use Thinktomorrow\AssetLibrary\Application\AddAsset;
use Thinktomorrow\Chief\Tests\Feature\Pages\PageFormParams;
use Thinktomorrow\Chief\Tests\Feature\Media\Fakes\FileFieldManager;

class DetachFileFieldValueTest extends TestCase
{
    const FILEFIELD_KEY = 'fake-file';

    use PageFormParams;

    private $page;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpDefaultAuthorization();

        app(Register::class)->register(FileFieldManager::class, Single::class);
        $this->page = Single::create();
    }

    /** @test */
    public function an_asset_can_be_removed()
    {
        // Upload existing asset
        app(AddAsset::class)->add($this->page, UploadedFile::fake()->image('image.png'), static::FILEFIELD_KEY, 'nl');
        $this->assertCount(1, $this->page->assets(static::FILEFIELD_KEY));

        $this->detachFileRequest([
            'nl' => [
                $this->page->assets(static::FILEFIELD_KEY)->first()->id => null,
            ],
        ]);

        $this->assertCount(0, $this->page->fresh()->assets());
        $this->assertCount(1, Asset::all());
    }

    /** @test */
    public function an_image_can_be_removed_alongside_invalid_values()
    {
        // Upload existing asset
        app(AddAsset::class)->add($this->page, UploadedFile::fake()->image('image.png'), static::FILEFIELD_KEY, 'nl');

        $this->detachFileRequest([
            'nl' => [
                $this->page->assets(static::FILEFIELD_KEY)->first()->id => null,
                null => null,
            ],
        ]);

        $this->assertCount(0, $this->page->fresh()->assets());
        $this->assertCount(1, Asset::all());
    }

    /** @test */
    public function an_asset_can_be_removed_and_uploaded()
    {
        // Upload existing asset
        app(AddAsset::class)->add($this->page, UploadedFile::fake()->image('image.png'), static::FILEFIELD_KEY, 'nl');

        $this->detachFileRequest([
            'nl' => [
                    $this->page->assets(static::FILEFIELD_KEY)->first()->id => null,
                    UploadedFile::fake()->image('image.jpg'), // new
            ],
        ]);

        $this->assertCount(1, $this->page->fresh()->assets());
        $this->assertEquals('image.jpg', $this->page->refresh()->asset(static::FILEFIELD_KEY, 'nl')->filename());
    }

    /** @test */
    public function it_can_remove_translatable_images()
    {
        app(AddAsset::class)->add($this->page, UploadedFile::fake()->image('image.png'), static::FILEFIELD_KEY, 'en');

        $existing_asset_en = $this->page->assets(static::FILEFIELD_KEY, 'en')->first();

        $this->asAdmin()
            ->put(route('chief.back.managers.update', ['singles', $this->page->id]), $this->validUpdatePageParams([
                'files' => [
                    static::FILEFIELD_KEY => [
                        'nl' => [
                            UploadedFile::fake()->image('tt-favicon-nl.png'), // new
                        ],
                        'en' => [
                            $existing_asset_en->id => null, // detach
                        ]
                    ]
                ]
            ]));

        $this->assertEquals('tt-favicon-nl.png', $this->page->refresh()->asset(static::FILEFIELD_KEY, 'nl')->filename());
        $this->assertEquals('tt-favicon-nl.png', $this->page->asset(static::FILEFIELD_KEY, 'en')->filename());
    }

    private function detachFileRequest($payload)
    {
        return $this->asAdmin()
            ->put(route('chief.back.managers.update', ['singles', $this->page->id]), [
                'files' => [
                    static::FILEFIELD_KEY => $payload,
                ],
            ]);
    }
}
