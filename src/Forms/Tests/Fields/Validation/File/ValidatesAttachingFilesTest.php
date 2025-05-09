<?php

namespace Thinktomorrow\Chief\Forms\Tests\Fields\Validation\File;

use Illuminate\Validation\ValidationException;
use Thinktomorrow\Chief\Assets\Tests\TestSupport\TestingFileUploads;
use Thinktomorrow\Chief\Forms\App\Queries\Fields;
use Thinktomorrow\Chief\Forms\Fields\File;
use Thinktomorrow\Chief\Forms\Fields\Validation\FieldValidator;
use Thinktomorrow\Chief\Forms\Tests\TestSupport\PageWithAssets;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

class ValidatesAttachingFilesTest extends ChiefTestCase
{
    use TestingFileUploads;

    private File $field;

    private FieldValidator $fieldValidator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->fieldValidator = app(FieldValidator::class);

        $this->field = File::make('thumb')->locales(['nl', 'en'])->required();

        PageWithAssets::setFieldsDefinition(fn () => [
            $this->field,
        ]);
    }

    public function test_it_fails_validation(): void
    {
        $this->createExistingImage();

        $this->expectException(ValidationException::class);

        $payload = [
            'files' => ['thumb' => [
                'nl' => [
                    'attach' => [
                        $this->fileFormPayload(['id' => 1]),
                    ],
                ],
                'en' => [],
            ]],
        ];

        try {
            $this->fieldValidator->handle(Fields::make([$this->field]), $payload);
        } catch (ValidationException $e) {
            $this->assertEmpty($e->validator->errors()->first('files.thumb.nl'));
            $this->assertEquals('en thumb is verplicht.', $e->validator->errors()->first('files.thumb.en'));
            throw $e;
        }
    }

    public function test_it_passes_validation()
    {
        $this->createExistingImage();

        $payload = [
            'files' => ['thumb' => [
                'nl' => [
                    'attach' => [
                        $this->fileFormPayload(['id' => 1]),
                    ],
                ],
                'en' => [
                    'attach' => [
                        $this->fileFormPayload(['id' => 1]),
                    ],
                ],
            ]],
        ];

        $this->fieldValidator->handle(Fields::make([$this->field]), $payload);

        $this->assertTrue(true);
    }
}
