<?php

namespace Thinktomorrow\Chief\Sites\Tests\App;

use Illuminate\Validation\ValidationException;
use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Forms\Layouts\Form;
use Thinktomorrow\Chief\Models\App\Actions\ModelApplication;
use Thinktomorrow\Chief\Models\App\Actions\UpdateForm;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePageResource;

class UpdateFormWithScopedLocalesTest extends ChiefTestCase
{
    private ArticlePage $model;

    protected function setUp(): void
    {
        parent::setUp();

        ArticlePage::migrateUp();
        chiefRegister()->resource(ArticlePageResource::class);

        $this->model = ArticlePage::create([
            'allowed_sites' => ['nl', 'en'],
        ]);
    }

    public function test_it_only_validates_page_scoped_locales()
    {
        $this->expectException(ValidationException::class);

        ArticlePageResource::setFieldsDefinition(function () {
            return [
                Form::make('main')->items([
                    Text::make('title_trans')->locales()->required(),
                ]),
            ];
        });

        try {
            app(ModelApplication::class)->updateForm(new UpdateForm(
                $this->model->modelReference(),
                ['nl', 'en'],
                'main',
                ['title_trans' => ['nl' => '', 'fr' => null, 'en' => '']], []
            ));
        } catch (ValidationException $e) {

            $this->assertEquals([
                'title_trans.nl' => ['The nl title_trans field is required.'],
                'title_trans.en' => ['The en title_trans field is required.'],
            ], $e->errors());

            throw $e;
        }
    }

    public function test_it_saves_scoped_locales_but_still_saves_other_locales()
    {
        ArticlePageResource::setFieldsDefinition(function () {
            return [
                Form::make('main')->items([
                    Text::make('title_trans')->locales()->required(),
                ]),
            ];
        });

        app(ModelApplication::class)->updateForm(new UpdateForm(
            $this->model->modelReference(),
            ['nl', 'en'],
            'main',
            ['title_trans' => ['nl' => 'title nl', 'fr' => 'title fr', 'en' => 'title en']], []
        ));

        $this->assertEquals('title nl', $this->model->fresh()->dynamic('title_trans', 'nl'));
        $this->assertEquals('title en', $this->model->fresh()->dynamic('title_trans', 'en'));
    }

    public function test_it_saves_scoped_locales_and_keeps_other_locale_values()
    {
        ArticlePageResource::setFieldsDefinition(function () {
            return [
                Form::make('main')->items([
                    Text::make('title_trans')->locales()->required(),
                ]),
            ];
        });

        $this->model->setDynamic('title_trans', 'title fr', 'fr');
        $this->model->save();

        app(ModelApplication::class)->updateForm(new UpdateForm(
            $this->model->modelReference(),
            ['nl', 'en'],
            'main',
            ['title_trans' => ['nl' => 'title nl', 'en' => 'title en']], []
        ));

        $this->assertEquals('title nl', $this->model->fresh()->dynamic('title_trans', 'nl'));
        $this->assertEquals('title fr', $this->model->fresh()->dynamic('title_trans', 'fr'));
        $this->assertEquals('title en', $this->model->fresh()->dynamic('title_trans', 'en'));
    }
}
