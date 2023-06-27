<?php

namespace Thinktomorrow\Chief\Forms\Tests\Fields\ValidationRules;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Thinktomorrow\Chief\Forms\Fields\File;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\PageWithAssets;

class FileRequiredRuleTest extends ChiefTestCase
{
    private $model;

    protected function setUp(): void
    {
        parent::setUp();

        PageWithAssets::migrateUp();
        chiefRegister()->resource(PageWithAssets::class);

        $this->model = PageWithAssets::create();
    }

    public function test_it_fails_an_upload_if_file_is_required()
    {
        PageWithAssets::setFieldsDefinition(function () {
            return [
                File::make('thumb')->locales(['nl', 'en'])->required(),
            ];
        });

        $response = $this->asAdmin()->put($this->manager($this->model)->route('update', $this->model), [
            'files' => [
                'thumb' => [
                    'nl' => [null],
                    'en' => [],
                ],
            ],
        ]);

        $response->assertSessionHasErrors('files.thumb.nl');

        $this->assertCount(0, $this->model->assets('thumb'));
    }

    public function test_it_fails_a_localized_upload_if_file_is_required()
    {
        UploadedFile::fake()->image('image.png', '50', '50')->storeAs('test', 'image-temp-name.png');

        PageWithAssets::setFieldsDefinition(function () {
            return [
                File::make('thumb')->locales(['nl', 'en'])->required(),
            ];
        });

        $response = $this->asAdmin()->put($this->manager($this->model)->route('update', $this->model), [
            'files' => [
                'thumb' => [
                    'nl' => [
                        'uploads' => [
                            [
                                'id' => 'xxx',
                                'path' => Storage::path('test/image-temp-name.png'),
                                'originalName' => 'image.png',
                                'mimeType' => 'image/png',
                                'fieldValues' => [],
                            ],
                        ],
                    ],
                    'en' => [],
                ],
            ],
        ]);

        $response->assertSessionHasErrors('files.thumb.en');
        $this->assertStringContainsString('thumb EN is verplicht.', session()->get('errors')->first('files.thumb.en'));

        $this->assertCount(0, $this->model->assets('thumb'));
    }

    public function test_it_passes_an_upload_if_file_is_required()
    {
        UploadedFile::fake()->image('image.png', '50', '50')->storeAs('test', 'image-temp-name.png');

        PageWithAssets::setFieldsDefinition(function () {
            return [
                File::make('thumb')->required(),
            ];
        });

        $response = $this->asAdmin()->put($this->manager($this->model)->route('update', $this->model), [
            'files' => [
                'thumb' => [
                    'nl' => [
                        'uploads' => [
                            [
                                'id' => 'xxx',
                                'path' => Storage::path('test/image-temp-name.png'),
                                'originalName' => 'image.png',
                                'mimeType' => 'image/png',
                                'fieldValues' => [],
                            ],
                        ],
                    ],
                ],
            ],
        ]);

        $response->assertSessionHasNoErrors();

        $this->assertCount(1, $this->model->assets('thumb'));
    }
}
