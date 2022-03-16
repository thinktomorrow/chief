<?php

namespace Thinktomorrow\Chief\Tests\Unit\Forms\Fields\Views;

use Illuminate\Support\Facades\Route;
use Thinktomorrow\Chief\Forms\Fields\Field;
use Thinktomorrow\Chief\Forms\Fields\File;
use Thinktomorrow\Chief\Forms\Fields\Image;
use Thinktomorrow\Chief\Forms\Fields\Media\FileDTO;
use Thinktomorrow\Chief\Tests\Unit\Forms\TestCase;

class RenderFileFieldTest extends TestCase
{
    private array $classes;

    public function setUp(): void
    {
        parent::setUp();

        // Required route for image - gallery image picker.
        Route::get('api/media', 'example@handle')->name('chief.api.media');

        $this->classes = [
            File::class => [$this->payload()],
            Image::class => [
                $this->payload([
                    'filename' => 'testfile.jpg',
                    'url' => '/media/testfile.jpg',
                    'thumbUrl' => '/media/testfile-thumb.jpg',
                    'mimetype' => 'image/jpeg',
                    'isImage' => true,
                ]),
            ],
        ];
    }

    /** @test */
    public function it_can_render_all_fields()
    {
        /** @var Field $class */
        foreach ($this->classes as $class => $value) {
            $component = $class::make('xxx')->value($value);
            $this->assertStringContainsString('name="files[xxx][nl]', $component->toHtml());
            $this->assertStringContainsString($value[0]->filename, $component->toHtml());
        }
    }

    /** @test */
    public function it_can_render_localized_fields()
    {
        /** @var Field $class */
        foreach (array_keys($this->classes) as $class) {
            $component = $class::make('xxx')->locales(['nl', 'en'])->value([
                'nl' => [$this->payload(['filename' => 'testfile-nl.pdf'])],
                'en' => [$this->payload(['filename' => 'testfile-en.pdf'])],
            ]);

            $render = $component->toHtml();

            $this->assertStringContainsString('files[xxx][nl]', $render);
            $this->assertStringContainsString('files[xxx][en]', $render);
            $this->assertStringContainsString('testfile-nl.pdf', $render);
            $this->assertStringContainsString('testfile-en.pdf', $render);
        }
    }

    /** @test */
    public function it_can_render_all_fields_in_a_window()
    {
        /** @var Field $class */
        foreach ($this->classes as $class => $value) {
            $component = $class::make('xxx')->editInSidebar()->value($value);
            $this->assertStringContainsString($value[0]->filename, $component->toHtml());
        }
    }

    private function payload(array $values = []): FileDTO
    {
        extract(array_merge([
            'id' => 1,
            'filename' => 'testfile.pdf',
            'url' => '/media/testfile.pdf',
            'thumbUrl' => '/media/testfile-thumb.pdf',
            'mimetype' => 'application/pdf',
            'isImage' => false,
            'size' => 1054,
            'extension' => 'jpg',
        ], $values));

        return new FileDTO($id, $filename, $url, $thumbUrl, $mimetype, $isImage, $size, $extension);
    }
}
