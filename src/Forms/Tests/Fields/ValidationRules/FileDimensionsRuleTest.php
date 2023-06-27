<?php

namespace Thinktomorrow\Chief\Forms\Tests\Fields\ValidationRules;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Thinktomorrow\AssetLibrary\Application\CreateAsset;
use Thinktomorrow\Chief\Forms\Fields\File;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\PageWithAssets;
use Thinktomorrow\Chief\Tests\Shared\PageFormParams;
use Thinktomorrow\Chief\Tests\Shared\UploadsFile;

class FileDimensionsRuleTest extends ChiefTestCase
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

    public function test_it_fails_an_upload_if_dimensions_are_invalid()
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

    public function test_it_pass_an_upload_if_dimensions_are_validat()
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

    public function test_it_fails_adding_asset_when_dimensions_are_invalid()
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

    public function test_it_can_add_asset_when_dimensions_are_valid()
    {
        $asset = app(CreateAsset::class)
            ->uploadedFile(UploadedFile::fake()->image('image.png', 50, 50))
            ->save();


        PageWithAssets::setFieldsDefinition(function () {
            return [
                File::make('thumb')->rules(['dimensions:min_width=40']),
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
}
