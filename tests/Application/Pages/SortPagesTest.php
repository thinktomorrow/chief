<?php

namespace Thinktomorrow\Chief\Tests\Application\Pages;

use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;

class SortPagesTest extends ChiefTestCase
{
    private $page;
    private $manager;

    protected function setUp(): void
    {
        parent::setUp();

        $this->page = $this->setupAndCreateArticle(['title' => 'Foobar', 'order' => 1]);
        $this->manager = $this->manager($this->page);
    }

    /** @test */
    public function it_can_sort_a_model()
    {
        $model2 = ArticlePage::create(['title' => 'Foobar 2', 'order' => 8]);
        $model3 = ArticlePage::create(['title' => 'Foobar 3', 'order' => 9]);

        $this->asAdmin()
            ->post($this->manager->route('sort-index'), [
                'indices' => [
                    $this->page->id,
                    $model3->id,
                    $model2->id,
                ],
            ]);

        $this->assertEquals(0, $this->page->fresh()->order);
        $this->assertEquals(1, $model3->fresh()->order);
        $this->assertEquals(2, $model2->fresh()->order);
    }

    /** @test */
    public function it_can_fetch_models_in_order_of_manual_sort()
    {
        $model1 = ArticlePage::create(['title' => 'Foobar', 'order' => 4]);
        $model2 = ArticlePage::create(['title' => 'Foobar 2', 'order' => 3]);
        $model3 = ArticlePage::create(['title' => 'Foobar 3', 'order' => 2]);

        $this->assertEquals([1,2,3,4], ArticlePage::sortedManually()->get()->pluck('order')->toArray());
    }
}
