<?php

namespace Thinktomorrow\Chief\Tests\Unit\Managers\Assistants\CrudAssistant;

use Illuminate\Http\UploadedFile;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\ManagedModels\Fields\Types\FileField;
use Thinktomorrow\Chief\ManagedModels\Fields\Types\InputField;
use Thinktomorrow\Chief\Tests\Shared\ManagerFactory;
use Thinktomorrow\Chief\Tests\Shared\ManagedModelFactory;
use Thinktomorrow\Chief\Managers\Assistants\CrudAssistant;

class StoreActionTest extends ChiefTestCase
{
    /** @test */
    public function it_can_create_a_model()
    {
        $model = ManagedModelFactory::make()->fields([
            InputField::make('title')->tag('create'),
        ])->withoutDatabaseInsert()->create();

        $manager = ManagerFactory::make()->withAssistants([CrudAssistant::class])->withModel($model)->create();

        $this->asAdmin()->post($manager->route('store'), [
            'title' => 'new-title',
        ]);

        $this->assertEquals(1, $model::count());
        $this->assertEquals('new-title', $model::first()->title);
    }

    /** @test */
    public function it_can_create_a_model_with_dynamic_field()
    {
        $model = ManagedModelFactory::make()->fields([
            InputField::make('dynamic_title')->tag('create'),
        ])->dynamicKeys(['dynamic_title'])->withoutDatabaseInsert()->create();

        $manager = ManagerFactory::make()->withAssistants([CrudAssistant::class])->withModel($model)->create();

        $this->asAdmin()->post($manager->route('store'), [
            'dynamic_title' => 'new-title',
        ]);

        $this->assertEquals(1, $model::count());
        $this->assertEquals('new-title', $model::first()->dynamic_title);
    }

    /** @test */
    public function it_can_create_a_model_with_dynamic_localized_field()
    {
        $model = ManagedModelFactory::make()->fields([
            InputField::make('dynamic_localized_title')->locales(['nl', 'en'])->tag('create'),
        ])->dynamicKeys(['dynamic_localized_title'])->withoutDatabaseInsert()->create();

        $manager = ManagerFactory::make()->withAssistants([CrudAssistant::class])->withModel($model)->create();

        $this->asAdmin()->post($manager->route('store'), [
            'trans' => [
                'nl' => [
                    'dynamic_localized_title' => 'dynamic title nl',
                ],
                'en' => [
                    'dynamic_localized_title' => 'dynamic title en',
                ],
            ],
        ]);

        $this->assertEquals(1, $model::count());
        $this->assertEquals('dynamic title nl', $model::first()->dynamic_localized_title);
        $this->assertEquals('dynamic title en', $model::first()->dynamic('dynamic_localized_title', 'en'));
    }

    /** @test */
    public function it_can_create_a_model_with_localized_field()
    {
        $model = ManagedModelFactory::make()->fields([
            InputField::make('title_trans')->locales(['nl', 'en'])->tag('create'),
        ])->translatedAttributes(['title_trans'])
            ->withoutDatabaseInsert()
            ->create();

        $manager = ManagerFactory::make()->withAssistants([CrudAssistant::class])->withModel($model)->create();

        $this->asAdmin()->post($manager->route('store'), [
            'trans' => [
                'nl' => [
                    'title_trans' => 'title nl',
                ],
                'en' => [
                    'title_trans' => 'title en',
                ],
            ],
        ]);

        $this->assertEquals(1, $model::count());
        $this->assertEquals('title nl', $model::first()->{'title_trans:nl'});
        $this->assertEquals('title en', $model::first()->{'title_trans:en'});
    }

    /** @test */
    public function it_can_upload_a_file_field()
    {
        $model = ManagedModelFactory::make()->fields([
            FileField::make('hero')->tag('create'),
        ])->withoutDatabaseInsert()->create();

        $manager = ManagerFactory::make()->withAssistants([CrudAssistant::class])->withModel($model)->create();

        $this->asAdmin()->post($manager->route('store'), [
            'files' => [
                'hero' => [
                    'nl' => [
                        UploadedFile::fake()->image('tt-favicon.png'),
                    ],
                ],
            ],
        ]);

        $this->assertEquals('tt-favicon.png', $model::first()->asset('hero')->filename());
    }
}
