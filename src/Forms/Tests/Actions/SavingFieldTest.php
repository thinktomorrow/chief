<?php

namespace Thinktomorrow\Chief\Forms\Tests\Actions;

use Thinktomorrow\Chief\Forms\App\Actions\SaveFields;
use Thinktomorrow\Chief\Forms\App\Queries\Fields;
use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Forms\Tests\TestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;

class SavingFieldTest extends TestCase
{
    public function test_a_field_can_have_custom_save_logic()
    {
        $field = Text::make('title')->save(function () {
            return 'foobar';
        });

        $this->assertEquals('foobar', call_user_func($field->getSave()));
    }

    public function test_a_custom_save_logic_can_be_performed()
    {
        ArticlePage::migrateUp();
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

    public function test_a_field_can_have_custom_set_logic()
    {
        $field = Text::make('title')->prepareValue(function () {
            return 'foobar';
        });

        $this->assertEquals('foobar', call_user_func($field->getPrepareValue()));
    }

    public function test_it_can_prepare_the_value_before_process()
    {
        ArticlePage::migrateUp();
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
        ArticlePage::migrateUp();
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

    public function test_it_can_save_localized_values_with_custom_formkey()
    {
        ArticlePage::migrateUp();
        $article = new ArticlePage;

        $field = Text::make('title_trans')->setFieldNameTemplate(':name.:locale')->locales(['nl', 'en'])->prepForSaving(function ($value, $input) {
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
}
