<?php

namespace Thinktomorrow\Chief\Tests\Feature\Common;

use Thinktomorrow\Chief\Pages\Page;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\States\PageState;
use Thinktomorrow\Chief\States\State\StatefulContract;
use Thinktomorrow\Chief\Tests\ChiefDatabaseTransactions;
use Thinktomorrow\Chief\States\Archivable\Archivable;
use Thinktomorrow\Chief\States\Publishable\Publishable;

class ArchiveTraitTest extends TestCase
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
class ArchivableTraitDummyClass extends Model implements StatefulContract
{
    use Archivable, Publishable;

    public $current_state = PageState::ARCHIVED;

    protected $table = 'dummy';

    public static function migrateUp()
    {
        Schema::create('dummy', function (Blueprint $table) {
            $table->string('current_state')->default(PageState::PUBLISHED)->nullable();
            $table->timestamps();
        });
    }

    public function state(): string
    {
        return $this->current_state;
    }

    public function changeState($state)
    {
        $this->current_state = $state;
    }
}
