<?php

namespace Thinktomorrow\Chief\Tests\Feature\Common;

use Thinktomorrow\Chief\Pages\Page;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\States\PageState;
use Thinktomorrow\Chief\States\State\StatefulContract;
use Thinktomorrow\Chief\States\Archivable\Archivable;
use Thinktomorrow\Chief\States\Publishable\Publishable;
use Thinktomorrow\Squanto\Domain\PageKey;

class ArchiveTraitTest extends TestCase
{
    /**
     * @var FeaturableTraitDummyClass
     */
    private $dummy;

    protected function setUp(): void
    {
        parent::setUp();

        $this->dummy = new ArchivableTraitDummyClass();
        ArchivableTraitDummyClass::migrateUp();
    }

    /** @test */
    public function it_can_check_if_the_model_is_archived()
    {
        $this->assertTrue($this->dummy->isArchived());
    }

    /** @test */
    public function it_can_archive_the_model()
    {
        $this->dummy->changeStateOf(PageState::KEY, PageState::ARCHIVED);

        $this->assertEquals(1, $this->dummy->isArchived());
    }

    /** @test */
    public function it_can_unarchive_the_model()
    {
        $this->dummy->changeStateOf(PageState::KEY, PageState::DRAFT);

        $this->assertEquals(1, !$this->dummy->isArchived());
    }

    /** @test */
    public function it_can_get_archived_pages()
    {
        factory(Page::class)->create(['current_state' => PageState::ARCHIVED]);
        factory(Page::class)->create(['current_state' => PageState::ARCHIVED]);
        factory(Page::class)->create(['current_state' => PageState::DRAFT]);

        $this->assertCount(2, Page::archived()->get());
        $this->assertCount(1, Page::unarchived()->get());
    }
}


/**
 * Class ValidationTraitDummyClass
 * @package Thinktomorrow\Chief\Models
 */
class ArchivableTraitDummyClass extends Model implements StatefulContract
{
    use Archivable;

    public $current_state = PageState::ARCHIVED;

    protected $table = 'dummy';

    public static function migrateUp()
    {
        Schema::create('dummy', function (Blueprint $table) {
            $table->string('current_state')->default(PageState::PUBLISHED)->nullable();
            $table->timestamps();
        });
    }

    public function stateOf($key): string
    {
        return $this->current_state;
    }

    public function changeStateOf($key, $state)
    {
        $this->current_state = $state;
    }
}
