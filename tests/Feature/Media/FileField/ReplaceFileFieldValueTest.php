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

class ReplaceFileFieldValueTest extends TestCase
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
        app(AddAsset::class)->add($this->page, UploadedFile::fake()->image('image.png'), static::FILEFIELD_KEY, 'nl');
    }

    /** @test */
    public function it_can_replace_localized_images()
    {
        $existing_asset_nl = $this->page->assets(static::FILEFIELD_KEY, 'nl')->first();

        $this->asAdmin()
            ->put(route('chief.back.managers.update', ['singles', $this->page->id]), $this->validUpdatePageParams([
                'files' => [
                    static::FILEFIELD_KEY => [
                        'nl' => [
                            $existing_asset_nl->id => UploadedFile::fake()->image('tt-favicon-nl.png'), // replace
                        ],
                        'en' => [
                            UploadedFile::fake()->image('tt-favicon-en.png'), // New
                        ]
                    ]
                ]
            ]));

        $this->assertEquals('tt-favicon-nl.png', $this->page->fresh()->asset(static::FILEFIELD_KEY, 'nl')->filename());
        $this->assertEquals('tt-favicon-en.png', $this->page->fresh()->asset(static::FILEFIELD_KEY, 'en')->filename());
    }

    /** @test */
    public function an_asset_can_be_replaced()
    {
        $existing_asset = $this->page->assets(static::FILEFIELD_KEY)->first();

        // Replace asset
        $this->asAdmin()
            ->put(route('chief.back.managers.update', ['singles', $this->page->id]), $this->validUpdatePageParams([
                'files' => [
                    static::FILEFIELD_KEY => [
                        'nl' => [
                            $existing_asset->id => $this->dummyUploadedFile('tt-document.pdf'),
                        ],
                    ]
                ]
            ]));

        // Assert replacement took place
        $this->assertCount(1, $this->page->fresh()->assets(static::FILEFIELD_KEY));
        $this->assertCount(2, Asset::all());

        $this->assertStringContainsString('tt-document.pdf', $this->page->fresh()->asset(static::FILEFIELD_KEY, 'nl')->filename());
    }

    /** @test */
    public function an_asset_can_be_replaced_alongside_invalid_values()
    {
        $existing_asset = $this->page->assets(static::FILEFIELD_KEY)->first();

        $this->asAdmin()
            ->put(route('chief.back.managers.update', ['singles', $this->page->id]), $this->validUpdatePageParams([
                'files' => [
                    static::FILEFIELD_KEY => [
                        'nl' => [
                            $existing_asset->id => UploadedFile::fake()->image('tt-favicon.png'),
                            null => null
                        ],
                    ]
                ]
            ]));

        // Assert replacement took place
        $this->assertCount(1, $this->page->fresh()->assets(static::FILEFIELD_KEY));
        $this->assertCount(2, Asset::all());

        $this->assertStringContainsString('tt-favicon.png', $this->page->fresh()->asset(static::FILEFIELD_KEY, 'nl')->filename());
    }

}
