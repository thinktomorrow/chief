<?php

namespace Thinktomorrow\Chief\Forms\Tests\Actions;

use Thinktomorrow\Chief\Forms\App\Actions\SaveFields;
use Thinktomorrow\Chief\Forms\App\Queries\Fields;
use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Forms\Tests\FormsTestCase;
use Thinktomorrow\Chief\Forms\Tests\TestSupport\ModelWithAstrotomicTranslations;

class SavingAstrotomicFieldsTest extends FormsTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        ModelWithAstrotomicTranslations::migrateUp();

        config()->set('translatable.locales', ['nl', 'en']);
    }

    public function test_it_sets_translated_value_on_model_if_not_dynamic()
    {
        $model = new ModelWithAstrotomicTranslations;

        $field = Text::make('title_trans')->locales(['nl', 'en']);

        (new SaveFields)->save($model, Fields::make([$field]), [
            'title_trans' => [
                'nl' => 'titel-nl',
                'en' => 'title-en',
            ],
        ], []);

        $this->assertEquals('titel-nl', $model->fresh()->getAttribute('title_trans:nl'));
        $this->assertEquals('title-en', $model->fresh()->getAttribute('title_trans:en'));
    }
}
