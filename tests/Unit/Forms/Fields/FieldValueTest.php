<?php

namespace Thinktomorrow\Chief\Tests\Unit\Forms\Fields;

use Carbon\Carbon;
use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Unit\Forms\TestCase;

class FieldValueTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        ArticlePage::migrateUp();
    }

    /** @test */
    public function it_can_set_and_get_the_value()
    {
        $field = Text::make('title');

        $this->assertNull($field->getValue());

        $field->value('some-value');
        $this->assertEquals('some-value', $field->getValue());

        $field->value(['one','two','three']);
        $this->assertEquals(['one','two','three'], $field->getValue());
    }

    /** @test */
    public function it_can_get_the_existing_model_value()
    {
        $model = ArticlePage::make(['updated_at' => Carbon::yesterday()]);
        $field = Text::make('updated_at');

        $this->assertNull($field->getValue()); // without model
        $this->assertEquals(Carbon::yesterday(), $field->model($model)->getValue()); // with model
    }

    /** @test */
    public function if_model_has_not_got_the_value_than_the_default_is_used()
    {
        $model = ArticlePage::make(['updated_at' => null]);
        $field = Text::make('updated_at')->value('default-value');

        $this->assertEquals('default-value', $field->getValue());
        $this->assertEquals('default-value', $field->getValue($model));
    }

    /** @test */
    public function it_can_get_the_existing_translated_model_value()
    {
        config()->set('chief.locales', ['nl','en','fr']);

        $model = ArticlePage::make(['content_trans' => [
            'nl' => 'existing content nl',
            'en' => 'existing content en',
        ]]);

        $field = Text::make('content_trans')
            ->model($model)
            ->locales(['nl', 'en', 'fr']);

        $this->assertEquals('existing content nl', $field->getValue('nl'));
        $this->assertEquals('existing content en', $field->getValue('en'));

        // It does not use a fallback for translated values
        $this->assertNull($field->getValue('fr'));
    }

    /** @test */
    public function it_allows_for_a_custom_value_resolver()
    {
        $field = Text::make('title')->value(function () {
            return 'custom value';
        });

        $this->assertEquals('custom value', $field->getValue());
    }

    /** @test */
    public function a_custom_resolved_value_always_trumps_the_default_value()
    {
        // Anything passed by value resolver trumps the default value
        $field = Text::make('title')->value('default-value')->value(function () {
            return 'custom value';
        });

        $this->assertEquals('custom value', $field->getValue());
    }
}
