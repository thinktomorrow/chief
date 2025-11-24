<?php

namespace Actions;

use Thinktomorrow\Chief\Forms\App\Actions\SaveFields;
use Thinktomorrow\Chief\Forms\App\Queries\Fields;
use Thinktomorrow\Chief\Forms\Fields\Checkbox;
use Thinktomorrow\Chief\Forms\Fields\MultiSelect;
use Thinktomorrow\Chief\Forms\Fields\Select;
use Thinktomorrow\Chief\Forms\Fields\SelectList;
use Thinktomorrow\Chief\Forms\Layouts\Form;
use Thinktomorrow\Chief\Forms\Tests\FormsTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePageResource;

class SavingFieldsWithMultipleValuesTest extends FormsTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        ArticlePage::migrateUp();
    }

    private function fields(bool $allowMultiple = false): array
    {
        $fields = [
            Checkbox::make('tags_checkbox'),
            Select::make('tags_select'),
            MultiSelect::make('tags_multiselect'),
            SelectList::make('tags_selectlist'),
        ];

        if ($allowMultiple) {
            foreach ($fields as $field) {
                $field->multiple();
            }
        }

        return $fields;
    }

    public function test_it_saves_as_primitive_when_not_set_to_multiple(): void
    {
        ArticlePageResource::setFieldsDefinition(function () {
            return [
                Form::make('main')->items([
                    Checkbox::make('tags_checkbox'),
                    Select::make('tags_select'),
                    MultiSelect::make('tags_multiselect'),
                    SelectList::make('tags_selectlist'),
                ]),
            ];
        });

        $model = new ArticlePage;

        (new SaveFields)->save($model, Fields::make($this->fields()), [
            'tags_checkbox' => 'tag1',
            'tags_select' => 'tag2',
            'tags_multiselect' => 'tag3',
            'tags_selectlist' => 'tag4',
        ], []);

        $this->assertEquals('tag1', $model->fresh()->tags_checkbox);
        $this->assertEquals('tag2', $model->fresh()->tags_select);
        $this->assertEquals('tag3', $model->fresh()->tags_multiselect);
        $this->assertEquals('tag4', $model->fresh()->tags_selectlist);
    }

    public function test_it_saves_as_null_when_not_set_to_multiple_without_value(): void
    {
        ArticlePageResource::setFieldsDefinition(function () {
            return [
                Form::make('main')->items([
                    Checkbox::make('tags_checkbox'),
                    Select::make('tags_select'),
                    MultiSelect::make('tags_multiselect'),
                    SelectList::make('tags_selectlist'),
                ]),
            ];
        });

        $model = new ArticlePage;

        (new SaveFields)->save($model, Fields::make($this->fields()), [], []);

        $this->assertNull($model->fresh()->tags_checkbox);
        $this->assertNull($model->fresh()->tags_select);
        $this->assertNull($model->fresh()->tags_multiselect);
        $this->assertNull($model->fresh()->tags_selectlist);
    }

    public function test_it_saves_as_empty_array_when_set_to_multiple_without_values(): void
    {
        ArticlePageResource::setFieldsDefinition(function () {
            return [
                Form::make('main')->items([
                    Checkbox::make('tags_checkbox')->multiple(),
                    Select::make('tags_select')->multiple(),
                    MultiSelect::make('tags_multiselect')->multiple(),
                    SelectList::make('tags_selectlist')->multiple(),
                ]),
            ];
        });

        $model = new ArticlePage;

        (new SaveFields)->save($model, Fields::make($this->fields(true)), [], []);

        $this->assertEquals([], $model->fresh()->tags_checkbox);
        $this->assertEquals([], $model->fresh()->tags_select);
        $this->assertEquals([], $model->fresh()->tags_multiselect);
        $this->assertEquals([], $model->fresh()->tags_selectlist);
    }

    public function test_it_saves_as_array_when_set_to_multiple()
    {
        ArticlePageResource::setFieldsDefinition(function () {
            return [
                Form::make('main')->items([
                    Checkbox::make('tags_checkbox')->multiple(),
                    Select::make('tags_select')->multiple(),
                    MultiSelect::make('tags_multiselect')->multiple(),
                    SelectList::make('tags_selectlist')->multiple(),
                ]),
            ];
        });

        $model = new ArticlePage;

        (new SaveFields)->save($model, Fields::make($this->fields(true)), [
            'tags_checkbox' => ['tag1', 'tag2'],
            'tags_select' => ['tag3', 'tag4'],
            'tags_multiselect' => ['tag5', 'tag6'],
            'tags_selectlist' => ['tag7', 'tag8'],
        ], []);

        $this->assertEquals(['tag1', 'tag2'], $model->fresh()->tags_checkbox);
        $this->assertEquals(['tag3', 'tag4'], $model->fresh()->tags_select);
        $this->assertEquals(['tag5', 'tag6'], $model->fresh()->tags_multiselect);
        $this->assertEquals(['tag7', 'tag8'], $model->fresh()->tags_selectlist);
    }
}
