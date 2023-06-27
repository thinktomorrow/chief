<?php

namespace Fields\Validation;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Thinktomorrow\AssetLibrary\Application\CreateAsset;
use Thinktomorrow\Chief\Forms\Fields\File;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\PageWithAssets;
use Thinktomorrow\Chief\Tests\Shared\PageFormParams;
use Thinktomorrow\Chief\Tests\Shared\UploadsFile;

class ValidateFileTest extends ChiefTestCase
{
    use PageFormParams;
    use UploadsFile;

    private $model;
    private $manager;

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
                    'en' => [],
                ],
            ],
        ]);

        $response->assertSessionHasNoErrors();

        $this->assertCount(1, $this->model->assets('thumb'));
    }

    public function test_it_fails_an_upload_if_dimensions_are_invalidated()
    {
        UploadedFile::fake()->image('image.png', '50', '50')->storeAs('test', 'image-temp-name.png');

        PageWithAssets::setFieldsDefinition(function () {
            return [
                File::make('thumb')->rules(['dimensions:min_width=300']),
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

        $response->assertSessionHasErrors('files.thumb.nl');
        $this->assertStringContainsString('thumb heeft niet de juiste afmetingen: minimum breedte: 300px', session()->get('errors')->first('files.thumb.nl'));

        $this->assertCount(0, $this->model->assets('thumb'));
    }

    public function test_it_can_pass_an_upload_if_dimensions_are_validated()
    {
        UploadedFile::fake()->image('image.png', '50', '50')->storeAs('test', 'image-temp-name.png');

        PageWithAssets::setFieldsDefinition(function () {
            return [
                File::make('thumb')->rules(['dimensions:min_width=40']),
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

    public function test_it_fails_adding_asset_on_dimensions_rule()
    {
        $asset = app(CreateAsset::class)
            ->uploadedFile(UploadedFile::fake()->image('image.png', 50, 50))
            ->save();


        PageWithAssets::setFieldsDefinition(function () {
            return [
                File::make('thumb')->rules(['dimensions:min_width=300']),
            ];
        });

        $response = $this->asAdmin()->put($this->manager($this->model)->route('update', $this->model), [
            'files' => [
                'thumb' => [
                    'nl' => [
                        'attach' => [
                            ['id' => $asset->id],
                        ],
                    ],
                ],
            ],
        ]);

        $response->assertSessionHasErrors('files.thumb.nl');
        $this->assertStringContainsString('thumb heeft niet de juiste afmetingen: minimum breedte: 300px', session()->get('errors')->first('files.thumb.nl'));

        $this->assertCount(0, $this->model->assets('thumb'));
    }

    /** @test */
    public function it_can_validate_a_max_filesize()
    {
        $response = $this->uploadFile('thumb_trans', [
            'nl' => [UploadedFile::fake()->image('image.png', '1000', '800')],
            'en' => [],
        ]);

        $response->assertSessionHasErrors('files.thumb_trans.nl');
        $this->assertStringContainsString('thumb trans NL is te groot en dient kleiner te zijn dan', session()->get('errors')->first('files.thumb_trans.nl'));

        $this->assertCount(0, $this->model->assets('thumb_trans'));
    }

    /** @test */
    public function it_can_validate_a_min_filesize()
    {
        $response = $this->uploadFile('thumb_trans', [
            'nl' => [UploadedFile::fake()->image('image.png', '101', '101')],
            'en' => [],
        ]);

        $response->assertSessionHasErrors('files.thumb_trans.nl');
        $this->assertStringContainsString('thumb trans NL is te klein en dient groter te zijn dan', session()->get('errors')->first('files.thumb_trans.nl'));

        $this->assertCount(0, $this->model->assets('thumb_trans'));
    }

    /** @test */
    public function it_can_validate_a_mimetype()
    {
        $response = $this->uploadFile('thumb_trans', [
            'nl' => [UploadedFile::fake()->image('image.jpg', '200', '200')],
            'en' => [],
        ]);

        $response->assertSessionHasErrors('files.thumb_trans.nl');
        $this->assertStringContainsString('thumb trans NL is niet het juiste bestandstype', session()->get('errors')->first('files.thumb_trans.nl'));

        $this->assertCount(0, $this->model->assets('thumb_trans'));
    }

    /** @test */
    public function it_passed_file_validation_when_there_are_already_images_for_model_present()
    {
        $response = $this->uploadFile('thumb_trans', [
            'nl' => [2 => 2], // indicates there is already an asset on this model attached.
            'en' => [],
        ]);

        $response->assertSessionHasNoErrors();
    }
}
