<?php

namespace Thinktomorrow\Chief\Forms\Tests\Fields\Validation\File;

use Illuminate\Validation\ValidationException;
use Thinktomorrow\AssetLibrary\Asset;
use Thinktomorrow\Chief\Assets\Tests\TestSupport\TestingFileUploads;
use Thinktomorrow\Chief\Forms\App\Queries\Fields;
use Thinktomorrow\Chief\Forms\Fields\File;
use Thinktomorrow\Chief\Forms\Fields\Validation\FieldValidator;
use Thinktomorrow\Chief\Forms\Tests\TestSupport\PageWithAssets;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

class ValidatesExistingFilesTest extends ChiefTestCase
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
        $this->expectException(ValidationException::class);

        $this->createExistingImage();

        $payload = [
            'files' => ['thumb' => [
                'nl' => [
                    Asset::find(1),
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
