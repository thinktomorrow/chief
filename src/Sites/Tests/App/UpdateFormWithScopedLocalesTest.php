<?php

namespace Thinktomorrow\Chief\Sites\Tests\App;

use Illuminate\Validation\ValidationException;
use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Forms\Layouts\Form;
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
            'locales' => ['nl', 'fr'],
        ]);
    }

    public function test_it_only_validates_page_scoped_locales()
    {
        ArticlePageResource::setFieldsDefinition(function () {
            return [
                Form::make('main')->items([
                    Text::make('title_trans')->locales()->required(),
                ]),
            ];
        });

        $catched = false;

        try {
            // ModelReference $modelReference, string $formId, array $data, array $files
            app(UpdateForm::class)->handle($this->model->modelReference(), 'main', [
                'title_trans' => ['nl' => '', 'fr' => null, 'en' => ''],
            ], []);
        } catch (ValidationException $e) {

            $catched = true;

            $this->assertEquals([
                'title_trans.nl' => ['The title_trans NL field is required.'],
                'title_trans.fr' => ['The title_trans FR field is required.'],
            ], $e->errors());
        }

        $this->assertTrue($catched);

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

        app(UpdateForm::class)->handle($this->model->modelReference(), 'main', [
            'title_trans' => ['nl' => 'title nl', 'fr' => 'title fr', 'en' => 'title en'],
        ], []);

        $this->assertEquals('title nl', $this->model->fresh()->dynamic('title_trans', 'nl'));
        $this->assertEquals('title fr', $this->model->fresh()->dynamic('title_trans', 'fr'));
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

        $this->model->setDynamic('title_trans', 'title en', 'en');
        $this->model->save();

        app(UpdateForm::class)->handle($this->model->modelReference(), 'main', [
            'title_trans' => ['nl' => 'title nl', 'fr' => 'title fr'],
        ], []);

        $this->assertEquals('title nl', $this->model->fresh()->dynamic('title_trans', 'nl'));
        $this->assertEquals('title fr', $this->model->fresh()->dynamic('title_trans', 'fr'));
        $this->assertEquals('title en', $this->model->fresh()->dynamic('title_trans', 'en'));
    }
}
