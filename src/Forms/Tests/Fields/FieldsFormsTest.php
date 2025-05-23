<?php

namespace Thinktomorrow\Chief\Forms\Tests\Fields;

use Thinktomorrow\Chief\Forms\App\Queries\Fields;
use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Forms\Fields\Textarea;
use Thinktomorrow\Chief\Forms\Tests\FormsTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;

class FieldsFormsTest extends FormsTestCase
{
    public function test_it_accepts_fields()
    {
        $fields = Fields::make($values = [
            Text::make('xxx'),
            Text::make('yyy'),
        ]);

        $this->assertCount(2, $fields);
    }

    public function test_it_can_check_if_there_is_any_field()
    {
        $this->assertFalse(Fields::make([])->any());
        $this->assertTrue(Fields::make([])->isEmpty());

        $fields = Fields::make([
            Text::make('xxx'),
        ]);

        $this->assertTrue($fields->any());
        $this->assertFalse($fields->isEmpty());
    }

    public function test_it_can_return_all_fields()
    {
        $fields = Fields::make($values = [
            'xxx' => Text::make('xxx'),
            'yyy' => Text::make('yyy'),
        ]);

        $this->assertEquals(collect($values), $fields->all());
    }

    public function test_it_can_return_the_first_field()
    {
        $fields = Fields::make($values = [
            Text::make('xxx'),
            Text::make('yyy'),
        ]);

        $this->assertEquals($values[0], $fields->first());
    }

    public function test_it_can_find_a_field_by_key()
    {
        $fields = Fields::make($values = [
            Text::make('xxx'),
            Text::make('yyy'),
        ]);

        $this->assertEquals($values[1], $fields->find('yyy'));
    }

    public function test_a_field_not_found_by_key_throws_exception()
    {
        $this->expectException(\InvalidArgumentException::class);

        $fields = Fields::make([
            Text::make('xxx'),
        ]);

        $fields->find('unknown');
    }

    public function test_it_can_return_all_keys()
    {
        $fields = Fields::make($values = [
            Text::make('xxx'),
            Text::make('yyy'),
        ]);

        $this->assertEquals(['xxx', 'yyy'], $fields->keys());
    }

    public function test_it_can_filter_fields_by_key()
    {
        $fields = Fields::make($values = [
            Text::make('xxx'),
            Text::make('yyy'),
        ]);

        $this->assertEquals(collect([
            'xxx' => $values[0],
        ]), $fields->keyed(['xxx'])->all());
    }

    public function test_it_can_filter_fields_by_closure()
    {
        $fields = Fields::make($values = [
            Text::make('xxx'),
            Text::make('yyy'),
        ]);

        $this->assertEquals(collect([
            'yyy' => $values[1],
        ]), $fields->filterBy(function ($field) {
            return $field->getKey() == 'yyy';
        })->all());
    }

    public function test_it_can_filter_fields_by_tag()
    {
        $fields = Fields::make($values = [
            Text::make('xxx')->tag('foobar'),
            Text::make('yyy'),
        ]);

        $this->assertEquals(collect([
            'xxx' => $values[0],
        ]), $fields->filterByTagged('foobar')->all());
    }

    public function test_it_can_filter_fields_not_belonging_by_tag()
    {
        $fields = Fields::make($values = [
            Text::make('xxx'),
            Text::make('yyy')->tag('foobar'),
        ]);

        $this->assertEquals(collect([
            'xxx' => $values[0],
        ]), $fields->filterByNotTagged('foobar')->all());
    }

    public function test_it_can_filter_by_untagged_fields()
    {
        $fields = Fields::make($values = [
            Text::make('xxx'),
            Text::make('yyy')->tag('foobar'),
        ]);

        $this->assertEquals(collect([
            'xxx' => $values[0],
        ]), $fields->filterByUntagged()->all());
    }

    public function test_it_can_add_a_model_instance_to_each_field()
    {
        $fields = Fields::make($values = [
            Text::make('xxx'),
            Text::make('yyy'),
        ]);

        $fields = $fields->model($articlePage = new ArticlePage);

        foreach ($fields->all() as $field) {
            $this->assertEquals($articlePage, $field->getModel());
        }
    }

    public function test_it_can_remove_by_key()
    {
        $fields = Fields::make($values = [
            Text::make('xxx'),
            Text::make('yyy'),
        ]);

        $fields = $fields->remove('xxx');

        $this->assertCount(1, $fields->all());
        $this->assertEquals($values[1], $fields->first());
    }

    public function test_it_can_remove_by_keys()
    {
        $fields = Fields::make($values = [
            Text::make('xxx'),
            Text::make('yyy'),
        ]);

        $fields = $fields->remove(['xxx', 'yyy', 'zzz']);

        $this->assertCount(0, $fields->all());
    }

    public function test_it_can_remove_by_callable()
    {
        $fields = Fields::make($values = [
            Text::make('xxx'),
            Text::make('yyy'),
        ]);

        $fields = $fields->remove(fn ($field) => $field->getId() == 'xxx');

        $this->assertCount(1, $fields->all());
        $this->assertEquals($values[1], $fields->first());
    }

    public function test_it_can_merge_two_fields_objects()
    {
        $fields = Fields::make([
            Text::make('xxx'),
            Text::make('yyy'),
        ]);

        $fields2 = Fields::make([
            Text::make('aaa'),
            Text::make('bbb'),
        ]);

        $mergedFields = $fields->merge($fields2);

        $this->assertCount(4, $mergedFields->all());
        $this->assertEquals(['xxx', 'yyy', 'aaa', 'bbb'], $mergedFields->keys());
    }

    public function test_similar_keys_are_overwritten_with_the_latter()
    {
        $fields = Fields::make($values = [
            Text::make('xxx'),
            Text::make('yyy'),
        ]);

        $fields2 = Fields::make($values2 = [
            Textarea::make('xxx'),
        ]);

        $mergedFields = $fields->merge($fields2);

        // Explicitly check for 'key' because this is also a reserved callable in php: key();
        $this->assertCount(2, $mergedFields->all());
        $this->assertEquals(['xxx', 'yyy'], $mergedFields->keys());

        // Assert the first input is overwritten
        $this->assertInstanceOf(Textarea::class, $mergedFields->first());
    }

    public function test_similar_keys_are_overwritten_with_the_latter_when_setting_custom_key()
    {
        $fields = Fields::make($values = [
            Text::make('xxx'),
            Text::make('yyy'),
        ]);

        $fields2 = Fields::make($values2 = [
            Textarea::make('aaa')->key('xxx'),
        ]);

        $mergedFields = $fields->merge($fields2);

        // Explicitly check for 'key' because this is also a reserved callable in php: key();
        $this->assertCount(2, $mergedFields->all());
        $this->assertEquals(['xxx', 'yyy'], $mergedFields->keys());

        // Assert the first input is overwritten
        $this->assertInstanceOf(Textarea::class, $mergedFields->find('xxx'));
    }
}
