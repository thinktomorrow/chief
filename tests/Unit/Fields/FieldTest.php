<?php

namespace Thinktomorrow\Chief\Tests\Unit\Fields;

use Carbon\Carbon;
use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\Fields\Types\FieldType;
use Thinktomorrow\Chief\Fields\Types\InputField;
use Thinktomorrow\Chief\Tests\Feature\Audit\ArticleFake;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagedModelFake;

class FieldTest extends TestCase
{
    /** @test */
    function it_has_a_type()
    {
        $field = InputField::make('title');

        $this->assertEquals(FieldType::fromString(FieldType::INPUT), $field->getType());

        // Fieltype can be tested against
        $this->assertTrue($field->ofType(FieldType::INPUT));
        $this->assertFalse($field->ofType(FieldType::TEXT));
        $this->assertTrue($field->ofType(FieldType::TEXT, FieldType::INPUT));
    }

    /** @test */
    function it_uses_the_key_as_default_for_other_values()
    {
        $field = InputField::make('title');

        $this->assertEquals('title', $field->getKey());
        $this->assertEquals('title', $field->getName());
        $this->assertEquals('title', $field->getLabel());
        $this->assertEquals('title', $field->getColumn());
    }

    /** @test */
    function these_other_values_can_be_set_individually()
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
    function it_has_formfield_related_info()
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
    function the_formfield_info_can_be_localized()
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
    function it_can_set_and_get_the_value()
    {
        $field = InputField::make('title');

        $this->assertNull($field->getValue());

        $field->value('some-value');
        $this->assertEquals('some-value', $field->getValue());

        $field->value(['one','two','three']);
        $this->assertEquals(['one','two','three'], $field->getValue());
    }

    /** @test */
    function it_can_get_the_existing_model_value()
    {
        $model = ArticleFake::create(['updated_at' => Carbon::yesterday()]);
        $field = InputField::make('updated_at');

        $this->assertNull($field->getValue()); // without model
        $this->assertEquals(Carbon::yesterday(), $field->model($model)->getValue()); // with model
    }

    /** @test */
    function it_allows_for_a_custom_resolved_value()
    {
        $field = InputField::make('title')->valueResolver(function(){
            return 'custom value';
        });

        $this->assertEquals('custom value', $field->getValue());
    }

    /** @test */
    function a_custom_resolved_value_always_trumps_the_default_value()
    {
        // Anything passed by value resolver trumps the default value
        $field = InputField::make('title')->value('default-value')->valueResolver(function(){
            return 'custom value';
        });

        $this->assertEquals('custom value', $field->getValue());
    }

    /** @test */
    function if_model_has_not_got_the_value_than_the_default_is_used()
    {
        $model = ArticleFake::create(['updated_at' => null]);
        $field = InputField::make('updated_at')->value('default-value');

        $this->assertEquals('default-value', $field->getValue());
        $this->assertEquals('default-value', $field->getValue($model));
    }

    /** @test */
    function it_can_get_the_existing_translated_model_value()
    {
        $model = ArticleFake::create(['title:en' => 'existing title']);
        $field = InputField::make('title')->translatable(['nl','en']);

        $this->assertEquals('existing title', $field->model($model)->getValue('en'));
        $this->assertNull($field->getValue($model, 'nl'));
    }

    /** @test */
    function it_does_not_use_a_fallback_for__translated_values()
    {
        // Nl is default so make sure 'en' does not fallback to nl
        $model = ArticleFake::create(['title:nl' => 'existing title']);
        $field = InputField::make('title')->translatable(['nl','en']);

        $this->assertNull($field->model($model)->getValue('en'));
    }

    /** @test */
    function it_has_a_default_view()
    {
        $this->assertStringContainsString('<input type="text" name="test" id="test" class="input inset-s" placeholder="" value="">', InputField::make('test')->render());
    }

    /** @test */
    function it_can_set_locales()
    {
        $field = InputField::make('test');

        $this->assertEquals([], $field->getLocales());
        $this->assertFalse($field->isLocalized());

        $field->translatable(['nl','fr']);

        $this->assertEquals(['nl','fr'], $field->getLocales());
        $this->assertTrue($field->isLocalized());
    }
}
