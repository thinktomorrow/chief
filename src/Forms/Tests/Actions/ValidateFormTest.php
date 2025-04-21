<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Tests\Actions;

use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Forms\Layouts\Form;
use Thinktomorrow\Chief\Models\App\Actions\ModelApplication;
use Thinktomorrow\Chief\Models\App\Actions\UpdateForm;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePageResource;

final class ValidateFormTest extends ChiefTestCase
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
            app(ModelApplication::class)->updateForm(new UpdateForm(
                $this->model->modelReference(),
                ['nl', 'en'],
                'main',
                ['title_trans' => ['nl' => '', 'en' => '']], []
            ));
        } catch (ValidationException $e) {

            $catched = true;

            $this->assertEquals([
                'title_trans.nl' => ['The nl title_trans field is required.'],
                'title_trans.en' => ['The en title_trans field is required.'],
            ], $e->errors());
        }

        $this->assertTrue($catched);

    }

    public function test_a_required_field_can_be_validated()
    {

        $this->assertValidation(
            new ArticlePage,
            ['title' => 'The title field is required.'],
            $this->payload(['title' => '']),
            $this->manager($this->model)->route('edit', $this->model),
            $this->manager($this->model)->route('update', $this->model),
            1,
            'put'
        );
    }

    protected function payload($overrides = [])
    {
        $params = [
            'title' => 'title updated',
            'custom' => 'custom updated',
            'content_trans' => [
                'nl' => 'content nl updated',
                'en' => 'content en updated',
            ],
        ];

        foreach ($overrides as $key => $value) {
            Arr::set($params, $key, $value);
        }

        return $params;
    }

    public function test_a_field_can_be_validated()
    {
        $this->assertValidation(
            new ArticlePage,
            ['title' => 'The title field must be at least 4 characters.'],
            $this->payload(['title' => 'xx']),
            $this->manager($this->model)->route('edit', $this->model),
            $this->manager($this->model)->route('update', $this->model),
            1,
            'put'
        );
    }

    public function test_a_required_translatable_field_can_be_validated()
    {
        $this->assertValidation(
            new ArticlePage,
            'content_trans.nl',
            $this->payload(['content_trans.nl' => '', 'content_trans.en' => '']),
            $this->manager($this->model)->route('edit', $this->model),
            $this->manager($this->model)->route('update', $this->model),
            1,
            'put'
        );
    }

    public function test_a_required_translatable_field_can_be_validated_when_null_is_passed()
    {
        $this->assertValidation(
            new ArticlePage,
            'content_trans.nl',
            $this->payload(['content_trans.nl' => null]),
            $this->manager($this->model)->route('edit', $this->model),
            $this->manager($this->model)->route('update', $this->model),
            1,
            'put'
        );
    }

    public function test_a_non_default_translatable_field_is_not_validated_if_entire_translation_is_empty()
    {
        $response = $this->actingAs($this->developer(), 'chief')
            ->put($this->manager($this->model)->route('update', $this->model), $this->payload(['content_trans.en' => '']));

        $this->assertNull(session('errors'));
    }
}
