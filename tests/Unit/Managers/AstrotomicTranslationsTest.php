<?php

namespace Thinktomorrow\Chief\Tests\Unit\Managers;

use Thinktomorrow\Chief\Fragments\Database\FragmentRepository;
use Thinktomorrow\Chief\ManagedModels\Fields\Types\InputField;
use Thinktomorrow\Chief\Fragments\Assistants\FragmentAssistant;
use Thinktomorrow\Chief\Managers\Presets\FragmentManager;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\ManagedModelFactory;
use Thinktomorrow\Chief\Tests\Shared\ManagerFactory;

class AstrotomicTranslationsTest extends ChiefTestCase
{
    private $owner;

    public function setUp(): void
    {
        parent::setUp();

        $this->owner = $this->setupAndCreateArticle();
    }

    /** @test */
    public function it_can_store_a_fragment_with_astrotomic_translations()
    {
        $model = ManagedModelFactory::make()->fields([
            InputField::make('title_trans')->locales(['nl', 'en'])->tag('create'),
        ])->translatedAttributes(['title_trans'])
            ->withoutDatabaseInsert()
            ->create();

        $manager = ManagerFactory::make()->withAssistants([FragmentAssistant::class])->withModel($model)->create();

        $this->asAdmin()->post($manager->route('fragment-store', $this->owner), [
            'trans' => [
                'nl' => ['title_trans' => 'title_trans nl value'],
                'en' => ['title_trans' => 'title_trans en value'],
            ],

        ]);

        $this->assertEquals(1, $model::count());

        $fragment = app(FragmentRepository::class)->getByOwner($this->owner)->first();
        $this->assertInstanceOf(get_class($model), $fragment);

        app()->setLocale('nl');
        $this->assertEquals('title_trans nl value', $fragment->title_trans);

        app()->setLocale('en');
        $this->assertEquals('title_trans en value', $fragment->title_trans);
    }

    /** @test */
    public function it_can_update_a_fragment_with_astrotomic_translations()
    {
        $model = ManagedModelFactory::make()->fields([
            InputField::make('title_trans')->locales(['nl', 'en']),
        ])->translatedAttributes(['title_trans'])
            ->withoutDatabaseInsert()
            ->create();

        chiefRegister()->model(get_class($model), FragmentManager::class);
        $manager = app(Registry::class)->manager($model::managedModelKey());

        $this->asAdmin()->post($manager->route('fragment-store', $this->owner), [
            'title' => 'existing-title',
            'custom' => 'existing-custom-value',
        ]);

        $model = app(FragmentRepository::class)->getByOwner($this->owner)->last();

        $this->asAdmin()->put($manager->route('fragment-update', $model), [
            'trans' => [
                'nl' => ['title_trans' => 'title_trans nl value'],
                'en' => ['title_trans' => 'title_trans en value'],
            ],
        ]);

        $fragment = app(FragmentRepository::class)->getByOwner($this->owner)->last();
        $this->assertInstanceOf(get_class($model), $fragment);

        app()->setLocale('nl');
        $this->assertEquals('title_trans nl value', $fragment->title_trans);

        app()->setLocale('en');
        $this->assertEquals('title_trans en value', $fragment->title_trans);
    }
}
