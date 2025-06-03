<?php

namespace Thinktomorrow\Chief\Forms\Tests\Fields\Views;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Thinktomorrow\AssetLibrary\Application\CreateAsset;
use Thinktomorrow\Chief\Forms\Fields\Field;
use Thinktomorrow\Chief\Forms\Fields\File;
use Thinktomorrow\Chief\Forms\Fields\Image;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;

class RenderFileFieldTest extends ChiefTestCase
{
    use RefreshDatabase;

    private array $classes;

    private $asset;

    private ArticlePage $model;

    protected function setUp(): void
    {
        parent::setUp();

        $this->model = $this->setUpAndCreateArticle();

        $this->asset = app(CreateAsset::class)
            ->uploadedFile(UploadedFile::fake()->image('image.png'))
            ->save();

        $this->classes = [
            File::class => ['nl' => [$this->asset]],
            Image::class => ['nl' => [$this->asset]],
        ];
    }

    public function test_it_can_render_all_fields()
    {
        /** @var Field $class */
        foreach ($this->classes as $class => $value) {
            $component = $class::make('xxx')
                ->model($this->model)
                ->value($value);

            $this->assertStringContainsString('files[xxx][nl]', $component->toHtml());
            $this->assertStringContainsString('data-error-placeholder="files.xxx.nl', $component->toHtml());
        }
    }

    public function test_it_can_render_localized_fields()
    {
        $assetEn = app(CreateAsset::class)
            ->uploadedFile(UploadedFile::fake()->image('image-en.png'))
            ->save();

        /** @var Field $class */
        foreach (array_keys($this->classes) as $class) {
            $component = $class::make('xxx')
                ->model($this->model)
                ->locales(['nl', 'en'])
                ->value([
                    'nl' => [$this->asset],
                    'en' => [$assetEn],
                ]);

            $render = $component->toHtml();

            $this->assertStringContainsString('data-error-placeholder="files.xxx.nl', $render);
            $this->assertStringContainsString('files[xxx][nl]', $render);
            $this->assertStringContainsString('files[xxx][en]', $render);
        }
    }

    public function test_it_can_render_all_fields_in_a_window()
    {
        /** @var Field $class */
        foreach ($this->classes as $class => $value) {
            $component = $class::make('xxx')->model($this->model)->value($value);
            $this->assertStringContainsString($value['nl'][0]->getFileName(), $component->renderPreview()->render());
        }
    }
}
