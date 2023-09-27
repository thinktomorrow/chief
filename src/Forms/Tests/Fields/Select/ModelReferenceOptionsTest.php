<?php

namespace Thinktomorrow\Chief\Forms\Tests\Fields\Select;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReferencePresenter;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;

class ModelReferenceOptionsTest extends ChiefTestCase
{

    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_it_can_retrieve_options_from_model_references()
    {
        $models = collect([
            $this->setUpAndCreateArticle(),
            ArticlePage::create(['current_state' => 'published']),
        ]);

        $result = ModelReferencePresenter::toSelectValues($models)->toArray();

        $this->assertEquals([
            ['value' => 'article_page@1', 'label' => 'article page [offline]'],
            ['value' => 'article_page@2', 'label' => 'article page'],
        ], $result);
    }

    public function test_it_can_retrieve_options_from_model_references_with_group()
    {
        $models = collect([
            $this->setUpAndCreateArticle(),
            ArticlePage::create(['current_state' => 'published']),
        ]);

        $result = ModelReferencePresenter::toSelectValues($models, true)->toArray();

        $this->assertEquals([
            ['value' => 'article_page@1', 'label' => 'article page [offline]', 'group' => 'Article page'],
            ['value' => 'article_page@2', 'label' => 'article page', 'group' => 'Article page'],
        ], $result);
    }

    public function test_it_can_retrieve_grouped_options_from_model_references()
    {
        $models = collect([
            $this->setUpAndCreateArticle(),
            ArticlePage::create(['current_state' => 'published']),
        ]);

        $result = ModelReferencePresenter::toGroupedSelectValues($models)->toArray();

        $this->assertEquals([
            [
                'label' => 'Article page',
                'options' => [
                    ['value' => 'article_page@1', 'label' => 'article page [offline]', 'group' => 'Article page'],
                    ['value' => 'article_page@2', 'label' => 'article page', 'group' => 'Article page'],
                ],
            ],
        ], $result);
    }
    use RefreshDatabase;
}
