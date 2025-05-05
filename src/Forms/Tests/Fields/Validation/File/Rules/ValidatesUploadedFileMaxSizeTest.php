<?php

namespace Thinktomorrow\Chief\Forms\Tests\Fields\Validation\File\Rules;

use Illuminate\Validation\ValidationException;
use Thinktomorrow\Chief\Assets\Tests\TestSupport\TestingFileUploads;
use Thinktomorrow\Chief\Forms\App\Queries\Fields;
use Thinktomorrow\Chief\Forms\Fields\File;
use Thinktomorrow\Chief\Forms\Fields\Validation\FieldValidator;
use Thinktomorrow\Chief\Forms\Tests\TestSupport\PageWithAssets;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

class ValidatesUploadedFileMaxSizeTest extends ChiefTestCase
{
    use TestingFileUploads;

    private File $field;

    private FieldValidator $fieldValidator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->fieldValidator = app(FieldValidator::class);

        $this->field = File::make('thumb')->locales(['nl', 'en'])->rules(['max:1']);

        PageWithAssets::setFieldsDefinition(fn () => [
            $this->field,
        ]);
    }

    public function test_it_fails_validation()
    {
        $this->storeFakeImageOnDisk('image-temp-name.png', 'test', 1000, 1000);

        $this->expectException(ValidationException::class);

        $payload = [
            'files' => ['thumb' => [
                'nl' => [
                    'uploads' => [
                        $this->fileFormPayload(),
                    ],
                ],
                'en' => [
                    'uploads' => [
                        $this->fileFormPayload(),
                    ],
                ],
            ]],
        ];

        try {
            $this->fieldValidator->handle(Fields::make([$this->field]), $payload);
        } catch (ValidationException $e) {
            $this->assertEquals('nl thumb is te groot en dient kleiner te zijn dan 1Kb.', $e->validator->errors()->first('files.thumb.nl'));
            $this->assertEquals('en thumb is te groot en dient kleiner te zijn dan 1Kb.', $e->validator->errors()->first('files.thumb.en'));
            throw $e;
        }
    }

    public function test_it_passes_validation()
    {
        $this->storeFakeImageOnDisk('image-temp-name.png', 'test', 10, 10);

        $payload = [
            'files' => ['thumb' => [
                'nl' => [
                    'uploads' => [
                        $this->fileFormPayload(),
                    ],
                ],
                'en' => [
                    'uploads' => [
                        $this->fileFormPayload(),
                    ],
                ],
            ]],
        ];

        $this->fieldValidator->handle(Fields::make([$this->field]), $payload);

        $this->assertTrue(true);
    }
}
