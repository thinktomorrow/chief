<?php

namespace Thinktomorrow\Chief\Forms\Tests\Fields\Views;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Thinktomorrow\AssetLibrary\Application\CreateAsset;
use Thinktomorrow\Chief\Forms\Fields\Field;
use Thinktomorrow\Chief\Forms\Fields\File;
use Thinktomorrow\Chief\Forms\Fields\Image;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

class RenderFileFieldTest extends ChiefTestCase
{
    use RefreshDatabase;

    private array $classes;
    private $asset;

    public function setUp(): void
    {
        parent::setUp();

        $this->asset = app(CreateAsset::class)
            ->uploadedFile(UploadedFile::fake()->image('image.png'))
            ->save();

        $this->classes = [
            File::class => ['nl' => [$this->asset]],
            Image::class => ['nl' => [$this->asset]],
        ];
    }

    /** @test */
    public function it_can_render_all_fields()
    {
        /** @var Field $class */
        foreach ($this->classes as $class => $value) {
            $component = $class::make('xxx')->value($value);
            $this->assertStringContainsString('name="files[xxx][nl]', $component->toHtml());
            $this->assertStringContainsString($value['nl'][0]->getFileName(), $component->toHtml());
        }
    }

    /** @test */
    public function it_can_render_localized_fields()
    {
        $assetEn = app(CreateAsset::class)
            ->uploadedFile(UploadedFile::fake()->image('image-en.png'))
            ->save();

        /** @var Field $class */
        foreach (array_keys($this->classes) as $class) {
            $component = $class::make('xxx')->locales(['nl', 'en'])->value([
                'nl' => [$this->asset],
                'en' => [$assetEn],
            ]);

            $render = $component->toHtml();

            $this->assertStringContainsString('files[xxx][nl]', $render);
            $this->assertStringContainsString('files[xxx][en]', $render);
            $this->assertStringContainsString('image.png', $render);
            $this->assertStringContainsString('image-en.png', $render);
        }
    }

    /** @test */
    public function it_can_render_all_fields_in_a_window()
    {
        /** @var Field $class */
        foreach ($this->classes as $class => $value) {
            $component = $class::make('xxx')->editInSidebar()->value($value);
            $this->assertStringContainsString($value['nl'][0]->getFileName(), $component->toHtml());
        }
    }
}
