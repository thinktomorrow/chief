<?php

namespace Fields\ValidationRules;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Thinktomorrow\AssetLibrary\Application\CreateAsset;
use Thinktomorrow\Chief\Forms\Fields\File;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\PageWithAssets;

class FileMinRuleTest extends ChiefTestCase
{
    private $model;

    protected function setUp(): void
    {
        parent::setUp();

        PageWithAssets::migrateUp();
        chiefRegister()->resource(PageWithAssets::class);

        $this->model = PageWithAssets::create();
    }

    public function test_it_fails_to_upload_when_file_is_below_min_width()
    {
        UploadedFile::fake()->image('image.png', '50', '50')->storeAs('test', 'image-temp-name.png');

        PageWithAssets::setFieldsDefinition(function () {
            return [
                File::make('thumb')->rules(['min:1']),
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
        $this->assertStringContainsString('thumb is te klein en dient groter te zijn dan 1Kb.', session()->get('errors')->first('files.thumb.nl'));

        $this->assertCount(0, $this->model->assets('thumb'));
    }

    public function test_it_passes_upload_when_file_does_is_higher_than_min_width()
    {
        UploadedFile::fake()->image('image.png', '1000', '1000')->storeAs('test', 'image-temp-name.png');

        PageWithAssets::setFieldsDefinition(function () {
            return [
                File::make('thumb')->rules(['min:1']),
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

    public function test_it_fails_to_attach_asset_when_file_is_below_min_width()
    {
        $asset = app(CreateAsset::class)
            ->uploadedFile(UploadedFile::fake()->image('image.png', 50, 50))
            ->save();

        PageWithAssets::setFieldsDefinition(function () {
            return [
                File::make('thumb')->rules(['min:1']),
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
        $this->assertStringContainsString('thumb is te klein en dient groter te zijn dan 1Kb.', session()->get('errors')->first('files.thumb.nl'));

        $this->assertCount(0, $this->model->assets('thumb'));
    }

    public function test_it_passes_to_attach_asset_when_file_does_is_higher_than_min_width()
    {
        $asset = app(CreateAsset::class)
            ->uploadedFile(UploadedFile::fake()->image('image.png', 1000, 1000))
            ->save();

        PageWithAssets::setFieldsDefinition(function () {
            return [
                File::make('thumb')->rules(['min:1']),
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
