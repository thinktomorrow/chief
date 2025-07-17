<?php

declare(strict_types=1);

namespace App;

use Illuminate\Validation\ValidationException;
use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Forms\Layouts\Form;
use Thinktomorrow\Chief\Models\App\Actions\ModelApplication;
use Thinktomorrow\Chief\Models\App\Actions\UpdateModel;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePageResource;

final class ValidatesWhenUpdatingModelTest extends ChiefTestCase
{
    private ArticlePage $model;

    protected function setUp(): void
    {
        parent::setUp();

        ArticlePage::migrateUp();
        chiefRegister()->resource(ArticlePageResource::class);

        $this->model = ArticlePage::create([
            'allowed_sites' => ['nl', 'fr'],
        ]);

        ArticlePageResource::setFieldsDefinition(function () {
            return [
                Form::make('main')->items([
                    Text::make('title_trans')->locales()->required(),
                ]),
            ];
        });
    }

    public function test_it_only_validates_page_scoped_locales()
    {
        $this->expectException(ValidationException::class);

        try {
            // ModelReference $modelReference, string $formId, array $data, array $files
            app(ModelApplication::class)->updateModel(new UpdateModel(
                $this->model->modelReference(),
                ['nl', 'en'],
                ['title_trans' => ['nl' => '', 'en' => '']], []
            ));
        } catch (ValidationException $e) {
            $this->assertEquals([
                'title_trans.nl' => ['The nl title_trans field is required.'],
                'title_trans.en' => ['The en title_trans field is required.'],
            ], $e->errors());

            throw $e;
        }
    }
}
