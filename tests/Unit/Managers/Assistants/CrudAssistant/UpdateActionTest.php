<?php

namespace Thinktomorrow\Chief\Tests\Unit\Managers\Assistants\CrudAssistant;

use Illuminate\Http\UploadedFile;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\ManagedModels\Fields\Types\FileField;
use Thinktomorrow\Chief\ManagedModels\Fields\Types\InputField;
use Thinktomorrow\Chief\Tests\Shared\ManagerFactory;
use Thinktomorrow\Chief\Tests\Shared\ManagedModelFactory;
use Thinktomorrow\Chief\Managers\Assistants\CrudAssistant;

class UpdateActionTest extends ChiefTestCase
{
    /** @test */
    public function it_can_update_a_model()
    {
        $model = ManagedModelFactory::make()->fields([
            InputField::make('title'),
        ])->create(['title' => 'Originele titel']);

        $manager = ManagerFactory::make()
            ->withAssistants([CrudAssistant::class])
            ->withModel($model)
            ->create();

        $this->asAdmin()->put($manager->route('update', $model), [
            'title' => 'nieuwe titel',
        ]);

        $this->assertEquals('nieuwe titel', $model->fresh()->title);
    }

    /** @test */
    public function a_non_admin_cannot_update_a_model()
    {
        $model = ManagedModelFactory::make()->fields([
            InputField::make('title'),
        ])->create(['title' => 'Originele titel']);

        $manager = ManagerFactory::make()
            ->withAssistants([CrudAssistant::class])
            ->withModel($model)
            ->create();

        $this->put($manager->route('update', $model), [
            'title' => 'nieuwe titel',
        ])->assertStatus(302)
          ->assertRedirect(route('chief.back.login'));

        $this->assertEquals('Originele titel', $model->fresh()->title);
    }

    /** @test */
    public function it_can_update_a_model_with_dynamic_field()
    {
        $model = ManagedModelFactory::make()->fields([
            InputField::make('title'),
        ])->dynamicKeys(['title'])->create(['title' => 'Originele titel']);

        $manager = ManagerFactory::make()
            ->withAssistants([CrudAssistant::class])
            ->withModel($model)
            ->create();

        $this->asAdmin()->put($manager->route('update', $model), [
            'title' => 'nieuwe titel',
        ]);

        $this->assertEquals('nieuwe titel', $model->fresh()->title);
    }

    /** @test */
    public function it_can_update_a_model_with_dynamic_localized_field()
    {
        $model = ManagedModelFactory::make()->fields([
            InputField::make('title')->locales(['nl','en']),
        ])->dynamicKeys(['title'])->create(['title.nl' => 'Originele titel', 'title.en' => 'Original title']);

        $manager = ManagerFactory::make()
            ->withAssistants([CrudAssistant::class])
            ->withModel($model)
            ->create();

        $this->asAdmin()->put($manager->route('update', $model), [
            'trans' => [
                'nl' => ['title' => 'nieuwe titel'],
                'en' => ['title' => 'new title'],
            ],
        ]);

        $this->assertEquals(1, $model::count());
        $this->assertEquals('nieuwe titel', $model->fresh()->title);
        $this->assertEquals('new title', $model->fresh()->dynamic('title', 'en'));
    }

    /** @test */
    public function it_can_update_a_model_with_localized_field()
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
