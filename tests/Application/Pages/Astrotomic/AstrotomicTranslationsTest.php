<?php

namespace Thinktomorrow\Chief\Tests\Application\Pages\Astrotomic;

use Thinktomorrow\Chief\Managers\Presets\PageManager;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

use function app;
use function chiefRegister;

class AstrotomicTranslationsTest extends ChiefTestCase
{
    private $owner;

    protected function setUp(): void
    {
        parent::setUp();

        QuoteWithAstrotomicTranslations::migrateUp();
        chiefRegister()->resource(QuoteWithAstrotomicTranslations::class, PageManager::class);
    }

    public function test_it_can_store_a_model_with_astrotomic_translations()
    {
        $this->asAdmin()->post($this->manager(QuoteWithAstrotomicTranslations::class)->route('store'), [
            'trans' => [
                'nl' => ['title_trans' => 'title_trans nl value'],
                'en' => ['title_trans' => 'title_trans en value'],
            ],
        ]);

        $this->assertEquals(1, QuoteWithAstrotomicTranslations::count());

        $model = QuoteWithAstrotomicTranslations::first();
        @app()->setLocale('nl');
        $this->assertEquals('title_trans nl value', $model->title_trans);

        app()->setLocale('en');
        $this->assertEquals('title_trans en value', $model->title_trans);
    }

    public function test_it_can_update_a_model_with_astrotomic_translations()
    {
        $model = QuoteWithAstrotomicTranslations::create([
            'title_trans:nl' => 'existing nl value',
            'title_trans:en' => 'existing en value',
        ]);

        $this->asAdmin()->put($this->manager(QuoteWithAstrotomicTranslations::class)->route('update', $model), [
            'trans' => [
                'nl' => ['title_trans' => 'title_trans nl value'],
                'en' => ['title_trans' => 'title_trans en value'],
            ],
        ]);

        app()->setLocale('nl');
        $this->assertEquals('title_trans nl value', $model->fresh()->title_trans);

        app()->setLocale('en');
        $this->assertEquals('title_trans en value', $model->fresh()->title_trans);
    }
}
