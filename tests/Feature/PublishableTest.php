<?php

namespace Tests\Feature;

use Chief\Models\Publishable;
use Illuminate\Database\Eloquent\Model;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * Class ValidationTraitDummyClass
 * @package Chief\Models
 */
class PublishableTraitDummyClass extends Model
{
    use Publishable;

    public $published = false;

    public function save(array $options = []){
        //
    }
}

class PublishableTest extends TestCase
{

    /**
     * @var PublishableTraitDummyClass
     */
    private $dummy;

    protected function setUp()
    {
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

    }
}
