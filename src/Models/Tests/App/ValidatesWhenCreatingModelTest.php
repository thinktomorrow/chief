<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Models\Tests\App;

use Illuminate\Validation\ValidationException;
use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Forms\Layouts\Form;
use Thinktomorrow\Chief\Models\App\Actions\CreateModel;
use Thinktomorrow\Chief\Models\App\Actions\ModelApplication;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePageResource;

final class ValidatesWhenCreatingModelTest extends ChiefTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        ArticlePage::migrateUp();
        chiefRegister()->resource(ArticlePageResource::class);

        ArticlePageResource::setFieldsDefinition(function () {
            return [
                Form::make('main')->items([
                    Text::make('title_trans')->locales()->required(),
                ]),
            ];
        });
    }

    public function test_it_validates_input()
    {
        $this->expectException(ValidationException::class);

        try {
            // ModelReference $modelReference, string $formId, array $data, array $files
            app(ModelApplication::class)->create(new CreateModel(
                ArticlePage::class,
                ['nl', 'en'],
                ['title_trans' => ['nl' => '', 'en' => '']],
                []
            ));
        } catch (ValidationException $e) {
            $this->assertEquals([
                'title_trans.nl' => ['The nl title_trans field is required.'],
                'title_trans.en' => ['The en title_trans field is required.'],
            ], $e->errors());

            throw $e;
        }
    }

    public function test_it_only_validates_page_scoped_locales()
    {
        $this->expectException(ValidationException::class);

        try {
            // ModelReference $modelReference, string $formId, array $data, array $files
            app(ModelApplication::class)->create(new CreateModel(
                ArticlePage::class,
                ['nl'],
                ['title_trans' => ['nl' => '', 'en' => '']],
                []
            ));
        } catch (ValidationException $e) {
            $this->assertEquals([
                'title_trans.nl' => ['The title_trans field is required.'],
            ], $e->errors());

            throw $e;
        }
    }
}
