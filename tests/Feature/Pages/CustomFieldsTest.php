<?php

namespace Thinktomorrow\Chief\Tests\Feature\Pages;

use Thinktomorrow\Chief\Management\Register;
use Thinktomorrow\Chief\Pages\Application\CreatePage;
use Thinktomorrow\Chief\Tests\Fakes\ArticlePageWithCategories;
use Thinktomorrow\Chief\Tests\Fakes\ArticlePageWithCategoriesManager;
use Thinktomorrow\Chief\Tests\Fakes\Category;
use Thinktomorrow\Chief\Tests\TestCase;

class CustomFieldsTest extends TestCase
{
    use PageFormParams;

    private $page;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpChiefEnvironment();

        ArticlePageWithCategories::migrateUp();
        Category::migrateUp();

        app(Register::class)->register(ArticlePageWithCategoriesManager::class, ArticlePageWithCategories::class);

        $this->asAdmin()->post(route('chief.back.managers.store', 'articles_with_category_fake'), $this->validPageParams());

        $this->page = ArticlePageWithCategories::first();
    }


    /** @test */
    public function it_can_edit_a_page_with_a_custom_field()
    {
        $response = $this->asAdmin()
            ->put(route('chief.back.managers.update', ['articles_with_category_fake', $this->page->id]), $this->validUpdatePageParams([
                'custom' => 'foobar'
            ]));

        $this->assertEquals('foobar', $this->page->fresh()->custom);
    }


    /** @test */
    public function it_can_edit_a_page_with_a_custom_relation_field()
    {
        $category1 = Category::create(['title' => 'eerste category']);
        $category2 = Category::create(['title' => 'tweede category']);
        $category3 = Category::create(['title' => 'derde category']);

        $this->asAdmin()
            ->put(route('chief.back.managers.update', ['articles_with_category_fake', $this->page->id]), $this->validUpdatePageParams([
                'categories' => [$category1->id, $category3->id]
            ]));

        $this->assertCount(2, $this->page->categories);
        $this->assertEquals($category1->id, $this->page->categories[0]->id);
        $this->assertEquals($category3->id, $this->page->categories[1]->id);
    }
}
