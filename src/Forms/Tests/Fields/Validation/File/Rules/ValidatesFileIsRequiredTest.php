<?php

namespace Thinktomorrow\Chief\Forms\Tests\Fields\Validation\File\Rules;

use Illuminate\Validation\ValidationException;
use Thinktomorrow\AssetLibrary\Asset;
use Thinktomorrow\Chief\Assets\Tests\TestSupport\TestingFileUploads;
use Thinktomorrow\Chief\Forms\App\Queries\Fields;
use Thinktomorrow\Chief\Forms\Fields\File;
use Thinktomorrow\Chief\Forms\Fields\Validation\FieldValidator;
use Thinktomorrow\Chief\Forms\Tests\TestSupport\PageWithAssets;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

class ValidatesFileIsRequiredTest extends ChiefTestCase
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

    public function test_it_validates_rule()
    {
        $this->expectException(ValidationException::class);

        $payload = [
            'files' => ['thumb' => ['nl' => [null], 'en' => []]],
        ];

        try {
            $this->fieldValidator->handle(Fields::make([$this->field]), $payload);
        } catch (ValidationException $e) {
            $this->assertEquals('nl thumb is verplicht.', $e->validator->errors()->first('files.thumb.nl'));
            $this->assertEquals('en thumb is verplicht.', $e->validator->errors()->first('files.thumb.en'));
            throw $e;
        }
    }

    public function test_it_passes_validation()
    {
        $this->storeFakeImageOnDisk('test', 'image-temp-name.png');

        $field = File::make('thumb')->required();

        PageWithAssets::setFieldsDefinition(fn () => [$field]);

        $payload = [
            'files' => ['thumb' => [
                'nl' => [
                    'uploads' => [
                        $this->fileFormPayload(),
                    ],
                ],
            ]],
        ];

        $this->fieldValidator->handle(Fields::make([$field]), $payload);

        $this->assertTrue(true);
    }

    public function test_it_validates_per_locale()
    {
        $this->storeFakeImageOnDisk('test', 'image-temp-name.png');

        $this->expectException(ValidationException::class);

        $payload = [
            'files' => ['thumb' => [
                'nl' => [
                    'uploads' => [
                        $this->fileFormPayload(),
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

    public function test_it_validates_existing_file()
    {
        $this->createExistingImage();

        $payload = [
            'files' => ['thumb' => [
                'nl' => [
                    Asset::find(1),
                ],
                'en' => [
                    Asset::find(1),
                ],
            ]],
        ];

        $this->fieldValidator->handle(Fields::make([$this->field]), $payload);

        $this->assertTrue(true);
    }
}
