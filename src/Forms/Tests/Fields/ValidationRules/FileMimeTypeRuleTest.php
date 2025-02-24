<?php

namespace Fields\ValidationRules;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Thinktomorrow\AssetLibrary\Application\CreateAsset;
use Thinktomorrow\Chief\Forms\Fields\File;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\PageWithAssets;

class FileMimeTypeRuleTest extends ChiefTestCase
{
    private $model;

    protected function setUp(): void
    {
        parent::setUp();

        PageWithAssets::migrateUp();
        chiefRegister()->resource(PageWithAssets::class);

        $this->model = PageWithAssets::create();
    }

    public function test_it_fails_to_upload_when_file_has_invalid_mimetype()
    {
        UploadedFile::fake()->image('image.png', '50', '50')->storeAs('test', 'image-temp-name.png');

        PageWithAssets::setFieldsDefinition(function () {
            return [
                File::make('thumb')->rules(['mimetypes:image/jpg']),
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
        $this->assertStringContainsString('thumb is niet het juiste bestandstype. Volgende types zijn geldig: image/jpg', session()->get('errors')->first('files.thumb.nl'));

        $this->assertCount(0, $this->model->assets('thumb'));
    }

    public function test_it_passes_upload_when_file_has_valid_mimetype()
    {
        UploadedFile::fake()->image('image.png', '50', '50')->storeAs('test', 'image-temp-name.png');

        PageWithAssets::setFieldsDefinition(function () {
            return [
                File::make('thumb')->rules(['mimetypes:image/png']),
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

    public function test_it_fails_to_attach_asset_when_file_has_invalid_mimetype()
    {
        $asset = app(CreateAsset::class)
            ->uploadedFile(UploadedFile::fake()->image('image.png', 50, 50))
            ->save();

        PageWithAssets::setFieldsDefinition(function () {
            return [
                File::make('thumb')->rules(['mimetypes:image/jpg']),
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
        $this->assertStringContainsString('thumb is niet het juiste bestandstype. Volgende types zijn geldig: image/jpg', session()->get('errors')->first('files.thumb.nl'));

        $this->assertCount(0, $this->model->assets('thumb'));
    }

    public function test_it_passes_to_attach_asset_when_file_has_valid_width()
    {
        $asset = app(CreateAsset::class)
            ->uploadedFile(UploadedFile::fake()->image('image.png', 50, 50))
            ->save();

        PageWithAssets::setFieldsDefinition(function () {
            return [
                File::make('thumb')->rules(['mimetypes:image/png']),
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

        $response->assertSessionHasNoErrors();
        $this->assertCount(1, $this->model->assets('thumb'));
    }

    //    /** @test */
    //    public function it_can_validate_a_mimetype()
    //    {
    //        $response = $this->uploadFile('thumb_trans', [
    //            'nl' => [UploadedFile::fake()->image('image.jpg', '200', '200')],
    //            'en' => [],
    //        ]);
    //
    //        $response->assertSessionHasErrors('files.thumb_trans.nl');
    //        $this->assertStringContainsString('thumb trans NL is niet het juiste bestandstype', session()->get('errors')->first('files.thumb_trans.nl'));
    //
    //        $this->assertCount(0, $this->model->assets('thumb_trans'));
    //    }
    //
    //    /** @test */
    //    public function it_passed_file_validation_when_there_are_already_images_for_model_present()
    //    {
    //        $response = $this->uploadFile('thumb_trans', [
    //            'nl' => [2 => 2], // indicates there is already an asset on this model attached.
    //            'en' => [],
    //        ]);
    //
    //        $response->assertSessionHasNoErrors();
    //    }
}
