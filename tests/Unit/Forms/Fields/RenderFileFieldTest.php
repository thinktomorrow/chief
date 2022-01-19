<?php

namespace Thinktomorrow\Chief\Tests\Unit\Forms\Fields;

use Thinktomorrow\Chief\Forms\Fields\File;
use Thinktomorrow\Chief\Forms\Fields\Field;
use Thinktomorrow\Chief\Forms\Fields\Image;
use Thinktomorrow\Chief\Forms\Fields\Textarea;
use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\Forms\Fields\Number;
use Thinktomorrow\Chief\Forms\Fields\Media\FileDTO;

/**
 * @internal
 * @coversNothing
 */
class RenderFileFieldTest extends TestCase
{
    private array $classes;

    public function setUp(): void
    {
        parent::setUp();

        $this->classes = [
            File::class  => [$this->payload()],
            Image::class => [
                $this->payload([
                    'filename' => 'testfile.jpg',
                    'url'      => '/media/testfile.jpg',
                    'thumbUrl' => '/media/testfile-thumb.jpg',
                    'mimetype' => 'image/jpeg',
                    'isImage'  => true,
                ]),
            ],
        ];
    }

    /** @test */
    public function itCanRenderAllFields()
    {
        /** @var Field $class */
        foreach ($this->classes as $class => $value) {
            $component = $class::make('xxx')->value($value);
            $this->assertStringContainsString('name="files[xxx][nl]', $component->toHtml());
            $this->assertStringContainsString($value[0]->filename, $component->toHtml());
        }
    }

    /** @test */
    public function itCanRenderLocalizedFields()
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
    public function itCanRenderAllFieldsInAWindow()
    {
        /** @var Field $class */
        foreach ($this->classes as $class => $value) {
            $component = $class::make('xxx')->displayInWindow()->value($value);
            $this->assertStringContainsString($value[0]->filename, $component->toHtml());
        }
    }

    private function payload(array $values = []): FileDTO
    {
        extract(array_merge([
            'id'        => 1,
            'filename'  => 'testfile.pdf',
            'url'       => '/media/testfile.pdf',
            'thumbUrl'  => '/media/testfile-thumb.pdf',
            'mimetype'  => 'application/pdf',
            'isImage'   => false,
            'size'      => 1054,
            'extension' => 'jpg',
        ], $values));

        return new FileDTO($id, $filename, $url, $thumbUrl, $mimetype, $isImage, $size, $extension);
    }
}
