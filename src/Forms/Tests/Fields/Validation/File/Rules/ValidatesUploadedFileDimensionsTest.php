<?php

namespace Thinktomorrow\Chief\Forms\Tests\Fields\Validation\File\Rules;

use Illuminate\Validation\ValidationException;
use Thinktomorrow\Chief\Assets\Tests\TestSupport\TestingFileUploads;
use Thinktomorrow\Chief\Forms\App\Queries\Fields;
use Thinktomorrow\Chief\Forms\Fields\File;
use Thinktomorrow\Chief\Forms\Fields\Validation\FieldValidator;
use Thinktomorrow\Chief\Forms\Tests\TestSupport\PageWithAssets;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

class ValidatesUploadedFileDimensionsTest extends ChiefTestCase
{
    use TestingFileUploads;

    private File $field;

    private FieldValidator $fieldValidator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->fieldValidator = app(FieldValidator::class);

        $this->field = File::make('thumb')->locales(['nl', 'en'])->rules(['dimensions:min_width=20']);

        PageWithAssets::setFieldsDefinition(fn () => [
            $this->field,
        ]);
    }

    public function test_it_fails_validation()
    {
        $this->storeFakeImageOnDisk('image-temp-name.png');

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
            $this->assertEquals('nl thumb heeft niet de juiste afmetingen: minimum breedte: 20px', $e->validator->errors()->first('files.thumb.nl'));
            $this->assertEquals('en thumb heeft niet de juiste afmetingen: minimum breedte: 20px', $e->validator->errors()->first('files.thumb.en'));
            throw $e;
        }
    }

    public function test_it_passes_validation()
    {
        $this->storeFakeImageOnDisk('image-temp-name.png', 'test', 21);

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
