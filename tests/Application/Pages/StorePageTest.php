<?php

namespace Thinktomorrow\Chief\Tests\Application\Pages;

use Illuminate\Http\UploadedFile;
use Thinktomorrow\Chief\Forms\Fields\File;
use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\ManagerFactory;
use Thinktomorrow\Chief\Tests\Shared\PageFormParams;
use Thinktomorrow\Chief\Managers\Presets\PageManager;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\ManagedModelFactory;
use Thinktomorrow\Chief\Managers\Assistants\CrudAssistant;
use function chiefRegister;

class StorePageTest extends ChiefTestCase
{
    use PageFormParams;

    /** @test */
    public function it_can_create_a_model()
    {
        ArticlePage::migrateUp();
        chiefRegister()->model(ArticlePage::class, PageManager::class);

        $this->asAdmin()->post($this->manager(ArticlePage::managedModelKey())->route('store'), $this->validPageParams(['title' => 'new-title']));

        $this->assertEquals(1, ArticlePage::count());
        $this->assertEquals('new-title', ArticlePage::first()->title);
    }

    /** @test */
    public function it_can_create_a_model_with_dynamic_field()
    {
        $model = ManagedModelFactory::make()->fields([
            Text::make('dynamic_title')->tag('create'),
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
            Text::make('dynamic_localized_title')->locales(['nl', 'en'])->tag('create'),
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
            Text::make('title_trans')->locales(['nl', 'en'])->tag('create'),
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
            File::make('hero')->tag('create'),
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
