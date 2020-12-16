<?php

namespace Thinktomorrow\Chief\Tests\Feature\Common;

use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\Tests\ChiefDatabaseTransactions;

class FeaturableTest extends TestCase
{
    use ChiefDatabaseTransactions;

    /**
     * @var FeaturableDummy
     */
    private $dummy;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase();

        FeaturableDummy::migrateUp();

        $this->dummy = new FeaturableDummy();
    }

    /** @test */
    public function it_can_check_if_the_model_is_featured()
    {
        $result = $this->dummy->isFeatured();

        $this->assertFalse($result);
    }

    /** @test */
    public function it_can_feature_the_model()
    {
        $this->dummy->feature();

        $this->assertEquals(1, $this->dummy->isFeatured());
    }

    /** @test */
    public function it_can_unfeature_the_model()
    {
        $this->dummy->unfeature();

        $this->assertEquals(1, !$this->dummy->isFeatured());
    }

    /** @test */
    public function it_can_get_featured_pages()
    {
        factory(Page::class)->create(['featured' => 1]);
        factory(Page::class)->create(['featured' => 0]);
        factory(Page::class)->create(['featured' => 1]);

        $this->assertCount(2, Page::featured()->get());
    }
}
