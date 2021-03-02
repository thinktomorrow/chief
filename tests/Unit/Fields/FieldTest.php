<?php

namespace Thinktomorrow\Chief\Tests\Unit\Fields;

use Carbon\Carbon;
use Thinktomorrow\Chief\ManagedModels\Fields\Types\FieldType;
use Thinktomorrow\Chief\ManagedModels\Fields\Types\InputField;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\TestCase;

class FieldTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        ArticlePage::migrateUp();
    }

    /** @test */
    public function it_has_a_type()
    {
        $field = InputField::make('title');

        $this->assertEquals(FieldType::fromString(FieldType::INPUT), $field->getType());

        // Fieltype can be tested against
        $this->assertTrue($field->ofType(FieldType::INPUT));
        $this->assertFalse($field->ofType(FieldType::TEXT));
        $this->assertTrue($field->ofType(FieldType::TEXT, FieldType::INPUT));
    }

    /** @test */
    public function it_uses_the_key_as_default_for_other_values()
    {
        $field = InputField::make('title');

        $this->assertEquals('title', $field->getKey());
        $this->assertEquals('title', $field->getName());
        $this->assertEquals('title', $field->getLabel());
        $this->assertEquals('title', $field->getColumn());
    }

    /** @test */
    public function these_other_values_can_be_set_individually()
    {
        $field = InputField::make('title')
                    ->column('title-column')
                    ->name('title-name')
                    ->label('title-label');

        $this->assertEquals('title', $field->getKey());
        $this->assertEquals('title-name', $field->getName());
        $this->assertEquals('title-label', $field->getLabel());
        $this->assertEquals('title-column', $field->getColumn());
    }

    /** @test */
    public function it_has_formfield_related_info()
    {
        $field = InputField::make('title');

        $this->assertNull($field->getDescription());
        $this->assertNull($field->getPrepend());
        $this->assertNull($field->getAppend());
        $this->assertNull($field->getPlaceholder());

        $field->description('custom-description')
             ->prepend('custom-prepend')
             ->append('custom-append')
             ->placeholder('custom-placeholder');

        $this->assertEquals('custom-description', $field->getDescription());
        $this->assertEquals('custom-prepend', $field->getPrepend());
        $this->assertEquals('custom-append', $field->getAppend());
        $this->assertEquals('custom-placeholder', $field->getPlaceholder());
    }

    /** @test */
    public function the_formfield_info_can_be_localized()
    {
        $field = InputField::make('title')
            ->prepend(['nl' => 'prepend-nl', 'en' => 'prepend-en'])
            ->append(['nl' => 'append-nl', 'en' => 'append-en'])
            ->placeholder(['nl' => 'placeholder-nl', 'en' => 'placeholder-en']);

        $this->assertEquals('prepend-nl', $field->getPrepend('nl'));
        $this->assertEquals('append-nl', $field->getAppend('nl'));
        $this->assertEquals('placeholder-nl', $field->getPlaceholder('nl'));

        $this->assertEquals('prepend-en', $field->getPrepend('en'));
        $this->assertEquals('append-en', $field->getAppend('en'));
        $this->assertEquals('placeholder-en', $field->getPlaceholder('en'));

        // by default the first entry is given
        $this->assertEquals('prepend-nl', $field->getPrepend());
        $this->assertEquals('append-nl', $field->getAppend());
        $this->assertEquals('placeholder-nl', $field->getPlaceholder());
    }

    /** @test */
    public function it_can_set_and_get_the_value()
    {
        $field = InputField::make('title');

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
        $field = InputField::make('updated_at');

        $this->assertNull($field->getValue()); // without model
        $this->assertEquals(Carbon::yesterday(), $field->model($model)->getValue()); // with model
    }

    /** @test */
    public function if_model_has_not_got_the_value_than_the_default_is_used()
    {
        $model = ArticlePage::make(['updated_at' => null]);
        $field = InputField::make('updated_at')->value('default-value');

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
        $field = InputField::make('content_trans')->translatable(['nl', 'en', 'fr']);

        $this->assertEquals('existing content nl', $field->model($model)->getValue('nl'));
        $this->assertEquals('existing content en', $field->model($model)->getValue('en'));

        // It does not use a fallback for translated values
        $this->assertNull($field->getValue($model, 'fr'));
    }

    /** @test */
    public function it_allows_for_a_custom_value_resolver()
    {
        $field = InputField::make('title')->valueResolver(function () {
            return 'custom value';
        });

        $this->assertEquals('custom value', $field->getValue());
    }

    /** @test */
    public function a_custom_resolved_value_always_trumps_the_default_value()
    {
        // Anything passed by value resolver trumps the default value
        $field = InputField::make('title')->value('default-value')->valueResolver(function () {
            return 'custom value';
        });

        $this->assertEquals('custom value', $field->getValue());
    }

    /** @test */
    public function it_has_a_default_view()
    {
        $render = str_replace(["\r\n", "\n"], "", InputField::make('test')->render());
        $render = preg_replace('/\s+/', ' ', $render);

        $this->assertStringContainsString('<input type="text" name="test"', $render);
    }

    /** @test */
    public function it_can_have_a_custom_view()
    {
        $this->app['view']->addNamespace('test-views', __DIR__ . '/stubs/views');

        $render = InputField::make('input-one')->view('test-views::custom-field')->render();

        $this->assertEquals('this is a custom field view', $render);
    }

    /** @test */
    public function it_can_set_locales()
    {
        $field = InputField::make('test');

        $this->assertEquals([], $field->getLocales());
        $this->assertFalse($field->isLocalized());

        $field->translatable(['nl','fr']);

        $this->assertEquals(['nl','fr'], $field->getLocales());
        $this->assertTrue($field->isLocalized());
    }
}
