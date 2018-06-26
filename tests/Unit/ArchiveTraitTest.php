<?php

namespace Thinktomorrow\Chief\Tests\Unit;

use Thinktomorrow\Chief\Pages\Page;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\Tests\ChiefDatabaseTransactions;
use Thinktomorrow\Chief\Common\Traits\Archivable\Archivable;
use Thinktomorrow\Chief\Common\Publish\Publishable;

class ArchivableTest extends TestCase
{
    use ChiefDatabaseTransactions;

    /**
     * @var FeaturableTraitDummyClass
     */
    private $dummy;

    protected function setUp()
    {
        parent::setUp();

        $this->setUpDatabase();

        $this->dummy = new ArchivableTraitDummyClass();
        ArchivableTraitDummyClass::migrateUp();
    }

    /** @test */
    public function it_can_check_if_the_model_is_archived()
    {
        $result = $this->dummy->isArchived();

        $this->assertFalse($result);
    }

    /** @test */
    public function it_can_archive_the_model()
    {
        $this->dummy->archive();

        $this->assertEquals(1, $this->dummy->isArchived());
    }

    /** @test */
    public function it_can_unarchive_the_model()
    {
        $this->dummy->unarchive();

        $this->assertEquals(1, !$this->dummy->isArchived());
    }

    /** @test */
    public function it_can_get_archived_pages()
    {
        factory(Page::class)->create(['archived_at' => Carbon::now()]);
        factory(Page::class)->create(['archived_at' => null]);
        factory(Page::class)->create(['archived_at' => Carbon::now()]);

        $this->assertCount(2, Page::archived()->get());
        $this->assertCount(1, Page::unarchived()->get());
    }

    /** @test */
    public function it_works_as_expected_alongside_publishable()
    {
        factory(Page::class)->create(['archived_at' => Carbon::now(), 'published' => 1]);
        factory(Page::class)->create(['archived_at' => null, 'published' => 1]);
        factory(Page::class)->create(['archived_at' => null, 'published' => 0]);
        factory(Page::class)->create(['archived_at' => null, 'published' => 0]);
        factory(Page::class)->create(['archived_at' => Carbon::now(), 'published' => 0]);

        $this->assertCount(2, Page::archived()->get());
        $this->assertCount(3, Page::get());
        $this->assertCount(1, Page::published()->get());
        $this->assertCount(2, Page::drafted()->get());
        $this->assertCount(5, Page::withArchived()->get());
    }
}


/**
 * Class ValidationTraitDummyClass
 * @package Thinktomorrow\Chief\Models
 */
class ArchivableTraitDummyClass extends Model
{
    use Archivable, Publishable;

    public $archived = false;

    protected $table = 'dummy';

    public static function migrateUp()
    {
        Schema::create('dummy', function (Blueprint $table) {
            $table->timestamp('archived_at')->default(null)->nullable();
            $table->timestamps();
        });
    }
}
