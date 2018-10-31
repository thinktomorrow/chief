<?php

namespace Thinktomorrow\Chief\Tests\Feature\Common;

use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Tests\ChiefDatabaseTransactions;
use Thinktomorrow\Chief\Tests\TestCase;
use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Chief\Common\Publish\Publishable;

/**
 * Class ValidationTraitDummyClass
 * @package Thinktomorrow\Chief\Models
 */
class PublishableTraitDummyClass extends Model
{
    use Publishable;

    public $published = false;

    public function save(array $options = [])
    {
        //
    }
}

class PublishableTest extends TestCase
{
    use ChiefDatabaseTransactions;

    /**
     * @var PublishableTraitDummyClass
     */
    private $dummy;

    protected function setUp()
    {
        parent::setUp();

        $this->setUpDatabase();

        $this->dummy = new PublishableTraitDummyClass();
    }

    /** @test */
    public function it_can_check_if_the_model_is_published()
    {
        $result = $this->dummy->isPublished();

        $this->assertFalse($result);
    }

    /** @test */
    public function it_can_check_if_the_model_is_draft()
    {
        $result = $this->dummy->isDraft();

        $this->assertTrue($result);
    }

    /** @test */
    public function it_can_publish_the_model()
    {
        $this->dummy->publish();

        $this->assertEquals(1, $this->dummy->isPublished());
    }

    /** @test */
    public function it_can_draft_the_model()
    {
        $this->dummy->draft();

        $this->assertEquals(1, $this->dummy->isDraft());
    }

    /** @test */
    public function it_can_get_all_the_published_models()
    {
        factory(Page::class)->create(['published' => 1]);
        factory(Page::class)->create(['published' => 0]);
        factory(Page::class)->create(['published' => 1]);

        $this->assertCount(2, Page::getAllPublished());
    }

    /** @test */
    public function it_can_get_pages_sorted_by_published()
    {
        factory(Page::class)->create(['published' => 1]);
        factory(Page::class)->create(['published' => 0]);
        factory(Page::class)->create(['published' => 1]);

        $this->assertTrue(Page::sortedByPublished()->first()->isPublished());
        $this->assertFalse(Page::sortedByPublished()->get()->last()->isPublished());
    }
}
