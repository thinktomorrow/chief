<?php

namespace Thinktomorrow\Chief\Tests\Feature\Common;

use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\States\PageState;
use Thinktomorrow\Chief\States\State\StatefulContract;
use Thinktomorrow\Chief\Tests\TestCase;
use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Chief\States\Publishable\Publishable;

/**
 * Class ValidationTraitDummyClass
 * @package Thinktomorrow\Chief\Models
 */
class PublishableTraitDummyClass extends Model implements StatefulContract
{
    use Publishable;

    public $current_state = PageState::DRAFT;

    public function save(array $options = [])
    {
        //
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

class PublishableTraitTest extends TestCase
{
    /**
     * @var PublishableTraitDummyClass
     */
    private $dummy;

    protected function setUp(): void
    {
        parent::setUp();

        $this->dummy = new PublishableTraitDummyClass();
    }

    /** @test */
    public function it_can_check_if_the_model_is_published()
    {
        $this->assertFalse($this->dummy->isPublished());
    }

    /** @test */
    public function it_can_check_if_the_model_is_draft()
    {
        $this->assertTrue($this->dummy->isDraft());
    }

    /** @test */
    public function it_can_publish_the_model()
    {
        $this->dummy->changeStateOf(PageState::KEY, PageState::PUBLISHED);

        $this->assertTrue($this->dummy->isPublished());
    }

    /** @test */
    public function it_can_draft_the_model()
    {
        $this->dummy->changeStateOf(PageState::KEY, PageState::PUBLISHED);
        $this->dummy->changeStateOf(PageState::KEY, PageState::DRAFT);

        $this->assertTrue($this->dummy->isDraft());
    }

    /** @test */
    public function it_can_get_all_the_published_models()
    {
        factory(Page::class)->create(['current_state' => PageState::PUBLISHED]);
        factory(Page::class)->create(['current_state' => PageState::PUBLISHED]);
        factory(Page::class)->create(['current_state' => PageState::DRAFT]);

        $this->assertCount(2, Page::getAllPublished());
    }

    /** @test */
    public function it_can_fetch_all_drafts_when_previewMode_is_active()
    {
        $this->asAdmin();
        config()->set('thinktomorrow.chief.preview-mode', 'preview');

        factory(Page::class)->create(['current_state' => PageState::DRAFT]);

        $this->assertCount(1, Page::getAllPublished());
    }

    /** @test */
    public function it_will_not_fetch_all_drafts_when_previewMode_is_active_but_no_admin_is_logged()
    {
        config()->set('thinktomorrow.chief.preview-mode', 'preview');

        factory(Page::class)->create(['current_state' => PageState::DRAFT]);

        $this->assertCount(0, Page::getAllPublished());
    }

    /** @test */
    public function preview_mode_can_be_disabled()
    {
        $this->asAdmin();
        config()->set('thinktomorrow.chief.preview-mode', null);

        factory(Page::class)->create(['current_state' => PageState::DRAFT]);

        $this->assertCount(0, Page::getAllPublished());
    }
}
