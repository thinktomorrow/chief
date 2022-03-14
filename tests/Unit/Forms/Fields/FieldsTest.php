<?php

namespace Thinktomorrow\Chief\Tests\Unit\Forms\Fields;

use Thinktomorrow\Chief\Forms\Fields;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Unit\Forms\TestCase;

class FieldsTest extends TestCase
{
    /** @test */
    public function it_accepts_fields()
    {
        $fields = Fields::make($values = [
            Fields\Text::make('xxx'),
            Fields\Text::make('yyy'),
        ]);

        $this->assertCount(2, $fields);
    }

    /** @test */
    public function it_can_check_if_there_is_any_field()
    {
        $this->assertFalse(Fields::make([])->any());
        $this->assertTrue(Fields::make([])->isEmpty());

        $fields = Fields::make([
            Fields\Text::make('xxx'),
        ]);

        $this->assertTrue($fields->any());
        $this->assertFalse($fields->isEmpty());
    }

    /** @test */
    public function it_can_return_all_fields()
    {
        $fields = Fields::make($values = [
            'xxx' => Fields\Text::make('xxx'),
            'yyy' => Fields\Text::make('yyy'),
        ]);

        $this->assertEquals(collect($values), $fields->all());
    }

    /** @test */
    public function it_can_return_the_first_field()
    {
        $fields = Fields::make($values = [
            Fields\Text::make('xxx'),
            Fields\Text::make('yyy'),
        ]);

        $this->assertEquals($values[0], $fields->first());
    }

    /** @test */
    public function it_can_find_a_field_by_key()
    {
        $fields = Fields::make($values = [
            Fields\Text::make('xxx'),
            Fields\Text::make('yyy'),
        ]);

        $this->assertEquals($values[1], $fields->find('yyy'));
    }

    /** @test */
    public function a_field_not_found_by_key_throws_exception()
    {
        $this->expectException(\InvalidArgumentException::class);

        $fields = Fields::make([
            Fields\Text::make('xxx'),
        ]);

        $fields->find('unknown');
    }

    /** @test */
    public function it_can_return_all_keys()
    {
        $fields = Fields::make($values = [
            Fields\Text::make('xxx'),
            Fields\Text::make('yyy'),
        ]);

        $this->assertEquals(['xxx','yyy'], $fields->keys());
    }

    /** @test */
    public function it_can_filter_fields_by_key()
    {
        $fields = Fields::make($values = [
            Fields\Text::make('xxx'),
            Fields\Text::make('yyy'),
        ]);

        $this->assertEquals(collect([
            'xxx' => $values[0],
        ]), $fields->keyed(['xxx'])->all());
    }

    /** @test */
    public function it_can_filter_fields_by_closure()
    {
        $fields = Fields::make($values = [
            Fields\Text::make('xxx'),
            Fields\Text::make('yyy'),
        ]);

        $this->assertEquals(collect([
            'yyy' => $values[1],
        ]), $fields->filterBy(function ($field) {
            return $field->getKey() == 'yyy';
        })->all());
    }

    /** @test */
    public function it_can_filter_fields_by_tag()
    {
        $fields = Fields::make($values = [
            Fields\Text::make('xxx')->tag('foobar'),
            Fields\Text::make('yyy'),
        ]);

        $this->assertEquals(collect([
            'xxx' => $values[0],
        ]), $fields->tagged('foobar')->all());
    }

    /** @test */
    public function it_can_filter_fields_not_belonging_by_tag()
    {
        $fields = Fields::make($values = [
            Fields\Text::make('xxx'),
            Fields\Text::make('yyy')->tag('foobar'),
        ]);

        $this->assertEquals(collect([
            'xxx' => $values[0],
        ]), $fields->notTagged('foobar')->all());
    }

    /** @test */
    public function it_can_filter_by_untagged_fields()
    {
        $fields = Fields::make($values = [
            Fields\Text::make('xxx'),
            Fields\Text::make('yyy')->tag('foobar'),
        ]);

        $this->assertEquals(collect([
            'xxx' => $values[0],
        ]), $fields->untagged()->all());
    }

    /** @test */
    public function it_can_add_a_model_instance_to_each_field()
    {
        $fields = Fields::make($values = [
            Fields\Text::make('xxx'),
            Fields\Text::make('yyy'),
        ]);

        $fields = $fields->model($articlePage = new ArticlePage());

        foreach ($fields->all() as $field) {
            $this->assertEquals($articlePage, $field->getModel());
        }
    }

    /** @test */
    public function it_can_remove_by_keys()
    {
        $fields = Fields::make($values = [
            Fields\Text::make('xxx'),
            Fields\Text::make('yyy'),
        ]);

        $fields = $fields->remove(['xxx']);

        $this->assertCount(1, $fields->all());
        $this->assertEquals($values[1], $fields->first());
    }

    /** @test */
    public function it_can_merge_two_fields_objects()
    {
        $fields = Fields::make([
            Fields\Text::make('xxx'),
            Fields\Text::make('yyy'),
        ]);

        $fields2 = Fields::make([
            Fields\Text::make('aaa'),
            Fields\Text::make('bbb'),
        ]);

        $mergedFields = $fields->merge($fields2);

        $this->assertCount(4, $mergedFields->all());
        $this->assertEquals(['xxx','yyy','aaa','bbb'], $mergedFields->keys());
    }

    /** @test */
    public function similar_keys_are_overwritten_with_the_latter()
    {
        $fields = Fields::make($values = [
            Fields\Text::make('xxx'),
            Fields\Text::make('yyy'),
        ]);

        $fields2 = Fields::make($values2 = [
            Fields\Textarea::make('xxx'),
        ]);

        $mergedFields = $fields->merge($fields2);

        // Explicitly check for 'key' because this is also a reserved callable in php: key();
        $this->assertCount(2, $mergedFields->all());
        $this->assertEquals(['xxx','yyy'], $mergedFields->keys());

        // Assert the first input is overwritten
        $this->assertInstanceOf(Fields\Textarea::class, $mergedFields->first());
    }

    /** @test */
    public function similar_keys_are_overwritten_with_the_latter_when_setting_custom_key()
    {
        $fields = Fields::make($values = [
            Fields\Text::make('xxx'),
            Fields\Text::make('yyy'),
        ]);

        $fields2 = Fields::make($values2 = [
            Fields\Textarea::make('aaa')->key('xxx'),
        ]);

        $mergedFields = $fields->merge($fields2);

        // Explicitly check for 'key' because this is also a reserved callable in php: key();
        $this->assertCount(2, $mergedFields->all());
        $this->assertEquals(['xxx','yyy'], $mergedFields->keys());

        // Assert the first input is overwritten
        $this->assertInstanceOf(Fields\Textarea::class, $mergedFields->find('xxx'));
    }
}
