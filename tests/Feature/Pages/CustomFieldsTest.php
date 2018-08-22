<?php

namespace Thinktomorrow\Chief\Tests\Feature\Pages;

use Illuminate\Support\Facades\Route;
use Thinktomorrow\Chief\Pages\Application\CreatePage;
use Thinktomorrow\Chief\Tests\Fakes\ArticlePageWithCategories;
use Thinktomorrow\Chief\Tests\Fakes\Category;
use Thinktomorrow\Chief\Tests\TestCase;

class CustomFieldsTest extends TestCase
{
    use PageFormParams;

    private $page;

    protected function setUp()
    {
        parent::setUp();

        ArticlePageWithCategories::migrateUp();
        Category::migrateUp();

        $this->setUpDefaultAuthorization();

        $this->app['config']->set('thinktomorrow.chief.collections', [
            'articles' => ArticlePageWithCategories::class,
        ]);

        $this->page = app(CreatePage::class)->handle('articles', $this->validPageParams()['trans'], [], [], []);

        // For our project context we expect the page detail route to be known
        Route::get('pages/{slug}', function () {
        })->name('pages.show');
    }


    /** @test */
    public function it_can_edit_a_page_with_a_custom_field()
    {
        $response = $this->asAdmin()
            ->put(route('chief.back.pages.update', $this->page->id), $this->validUpdatePageParams([
                'custom_fields' => [
                    'custom' => 'foobar'
                ],
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
            ->put(route('chief.back.pages.update', $this->page->id), $this->validUpdatePageParams([
                'custom_fields' => [
                    'categories' => [$category1->id, $category3->id]
                ],
            ]));

        $this->assertCount(2, $this->page->categories);
        $this->assertEquals($category1->id, $this->page->categories[0]->id);
        $this->assertEquals($category3->id, $this->page->categories[1]->id);
    }
}
