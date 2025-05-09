<?php

namespace Thinktomorrow\Chief\Forms\Tests\Actions;

use Thinktomorrow\Chief\Forms\App\Actions\SaveFields;
use Thinktomorrow\Chief\Forms\App\Queries\Fields;
use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Forms\Tests\FormsTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;

class SavingFieldsTest extends FormsTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        ArticlePage::migrateUp();
    }

    public function test_it_saves_non_localized_fields()
    {
        $article = new ArticlePage;

        $field = Text::make('title');

        (new SaveFields)->save($article, Fields::make([$field]), [
            'title' => 'Hello World',
        ], []);

        $this->assertEquals('Hello World', $article->fresh()->title);
    }

    public function test_it_skips_custom_setters()
    {
        $article = new ArticlePage;

        $field = Text::make('title')->fillForSaving(function ($model) {
            $model->title = 'manually-set';
        });

        (new SaveFields)->save($article, Fields::make([$field]), ['title' => 'default'], []);

        $this->assertEquals('manually-set', $article->fresh()->title);
    }

    public function test_it_can_have_custom_save_logic()
    {
        $field = Text::make('title')->save(function () {
            return 'foobar';
        });

        $this->assertEquals('foobar', call_user_func($field->getSave()));
    }

    public function test_it_can_execute_custom_save_logic()
    {
        $article = new ArticlePage;

        $field = Text::make('title')->save(function ($model, $field, $input, $files) {
            $model->title = $input['title'].'-foobar';
            $model->save();
        });

        (new SaveFields)->save($article, Fields::make([$field]), [
            'title' => 'xxx',
        ], []);

        $this->assertEquals('xxx-foobar', $article->fresh()->title);
    }

    public function test_it_can_prepare_value_for_saving()
    {
        $article = new ArticlePage;

        $field = Text::make('title')->prepForSaving(function ($value, $input) {
            return $value.'-foobar';
        });

        (new SaveFields)->save($article, Fields::make([$field]), [
            'title' => 'xxx',
        ], []);

        $this->assertEquals('xxx-foobar', $article->fresh()->title);
    }

    public function test_it_can_prepare_localized_values_for_saving()
    {
        $article = new ArticlePage;

        $field = Text::make('title_trans')->locales(['nl', 'en'])->prepForSaving(function ($value, $input, $locale) {
            return $value.'-foobar';
        });

        (new SaveFields)->save($article, Fields::make([$field]), [
            'title_trans' => [
                'nl' => 'xxx-nl',
                'en' => 'xxx-en',
            ],
        ], []);

        $this->assertEquals('xxx-nl-foobar', $article->fresh()->dynamic('title_trans', 'nl'));
        $this->assertEquals('xxx-en-foobar', $article->fresh()->dynamic('title_trans', 'en'));
    }

    public function test_it_can_save_localized_values_with_custom_field_name()
    {
        $article = new ArticlePage;

        $field = Text::make('title_trans')->setFieldNameTemplate('trans.:locale.:name')->locales(['nl', 'en'])->prepForSaving(function ($value, $input) {
            return $value.'-foobar';
        });

        (new SaveFields)->save($article, Fields::make([$field]), [
            'trans' => [
                'nl' => ['title_trans' => 'xxx-nl'],
                'en' => ['title_trans' => 'xxx-en'],
            ],
        ], []);

        $this->assertEquals('xxx-nl-foobar', $article->fresh()->dynamic('title_trans', 'nl'));
        $this->assertEquals('xxx-en-foobar', $article->fresh()->dynamic('title_trans', 'en'));
    }
}
