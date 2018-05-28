<?php

namespace Thinktomorrow\Chief\Tests\Unit;

use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Users\User;
use Thinktomorrow\Chief\Pages\Application\CreatePage;
use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Chief\Common\Traits\Featurable;
use Thinktomorrow\Chief\Common\Traits\Archivable;
use Illuminate\Support\Carbon;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\Tests\ChiefDatabaseTransactions;


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
}


/**
 * Class ValidationTraitDummyClass
 * @package Thinktomorrow\Chief\Models
 */
class ArchivableTraitDummyClass extends Model
{
    use Archivable;

    public $archived = false;

    protected $table = 'dummy';

    public static function migrateUp()
    {
        Schema::create('dummy', function(Blueprint $table){
            $table->timestamp('archived_at')->default(null)->nullable();
            $table->timestamps();
        });
    }
}