<?php

namespace Thinktomorrow\Chief\Forms\Tests\Fields\Validation\File\Rules;

use Illuminate\Validation\ValidationException;
use Thinktomorrow\Chief\Assets\Tests\TestSupport\TestingFileUploads;
use Thinktomorrow\Chief\Forms\App\Queries\Fields;
use Thinktomorrow\Chief\Forms\Fields\File;
use Thinktomorrow\Chief\Forms\Fields\Validation\FieldValidator;
use Thinktomorrow\Chief\Forms\Tests\TestSupport\PageWithAssets;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

class ValidatesUploadedFileMinSizeTest extends ChiefTestCase
{
    use TestingFileUploads;

    private File $field;

    private FieldValidator $fieldValidator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->fieldValidator = app(FieldValidator::class);

        $this->field = File::make('thumb')->locales(['nl', 'en'])->rules(['min:1']);

        PageWithAssets::setFieldsDefinition(fn () => [
            $this->field,
        ]);
    }

    public function test_it_fails_validation()
    {
        $this->storeFakeImageOnDisk('test', 'image-temp-name.png', 10, 10);

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
            $this->assertEquals('nl thumb is te klein en dient groter te zijn dan 1Kb.', $e->validator->errors()->first('files.thumb.nl'));
            $this->assertEquals('en thumb is te klein en dient groter te zijn dan 1Kb.', $e->validator->errors()->first('files.thumb.en'));
            throw $e;
        }
    }

    public function test_it_passes_validation()
    {
        $this->storeFakeImageOnDisk('test', 'image-temp-name.png', 1000, 1000);

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
