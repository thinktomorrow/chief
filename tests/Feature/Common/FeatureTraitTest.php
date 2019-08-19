<?php

namespace Thinktomorrow\Chief\Tests\Feature\Common;

use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Users\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Chief\Tests\TestCase;
use Illuminate\Database\Schema\Blueprint;
use Thinktomorrow\Chief\Concerns\Featurable;
use Thinktomorrow\Chief\Pages\Application\CreatePage;
use Thinktomorrow\Chief\Tests\ChiefDatabaseTransactions;

/**
 * Class ValidationTraitDummyClass
 * @package Thinktomorrow\Chief\Models
 */
class FeaturableTraitDummyClass extends Model
{
    use Featurable;

    public $featured = false;

    public function save(array $options = [])
    {
        //
    }

    public static function migrateUp()
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->boolean('featured')->default(false);
        });
    }
}

class FeaturableTest extends TestCase
{
    use ChiefDatabaseTransactions;

    /**
     * @var FeaturableTraitDummyClass
     */
    private $dummy;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase();

        FeaturableTraitDummyClass::migrateUp();

        $this->dummy = new FeaturableTraitDummyClass();
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
