<?php

namespace Thinktomorrow\Chief\Tests\Unit\Forms\Fields;

use Thinktomorrow\Chief\Forms\Fields;
use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Forms\SaveFields;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Unit\Forms\TestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;

//class SavingFieldTest extends ChiefTestCase
class SavingFieldTest extends TestCase
{
    /** @test */
    public function a_field_can_have_custom_save_logic()
    {
        $field = Text::make('title')->save(function () {
            return 'foobar';
        });

        $this->assertEquals('foobar', call_user_func($field->getSave()));
    }

    /** @test */
    public function a_custom_save_logic_can_be_performed()
    {
        ArticlePage::migrateUp();
        $article = new ArticlePage();

        $field = Text::make('title')->save(function ($model, $field, $input, $files) {
            $model->title = $input['title'] .  '-foobar';
            $model->save();
        });

        (new SaveFields)->save($article, Fields::make([$field]), [
            'title' => 'xxx',
        ], []);

        $this->assertEquals('xxx-foobar', $article->fresh()->title);
    }

    /** @test */
    public function a_field_can_have_custom_set_logic()
    {
        $field = Text::make('title')->setModelValue(function () {
            return 'foobar';
        });

        $this->assertEquals('foobar', call_user_func($field->getSetModelValue()));
    }

    /** @test */
    public function a_custom_set_logic_sets_the_value_for_saving()
    {
        ArticlePage::migrateUp();
        $article = new ArticlePage();

        $field = Text::make('title')->setModelValue(function ($model, $field, $input, $files) {
            $model->title = $input['title'] .  '-foobar';
        });

        (new SaveFields)->save($article, Fields::make([$field]), [
            'title' => 'xxx',
        ], []);

        $this->assertEquals('xxx-foobar', $article->fresh()->title);
    }

    /** @test */
    public function it_can_prepare_the_value_before_process()
    {
        ArticlePage::migrateUp();
        $article = new ArticlePage();

        $field = Text::make('title')->prepare(function ($value, $input) {
            return $value . '-foobar';
        });

        (new SaveFields)->save($article, Fields::make([$field]), [
            'title' => 'xxx',
        ], []);

        $this->assertEquals('xxx-foobar', $article->fresh()->title);
    }

    /** @test */
    public function it_can_prepare_localized_values()
    {
        ArticlePage::migrateUp();
        $article = new ArticlePage();

        $field = Text::make('title_trans')->locales(['nl','en'])->prepare(function ($value, $input) {
            return $value . '-foobar';
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
