<?php

namespace Thinktomorrow\Chief\Tests\Feature\Pages;

use Thinktomorrow\Chief\Pages\Single;
use Thinktomorrow\Chief\Tests\Fakes\ArticlePageFake;
use Thinktomorrow\Chief\Tests\Fakes\NewsletterModuleFake;
use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\Pages\PageManager;
use Thinktomorrow\Chief\Management\Register;

class DuplicatePageTest extends TestCase
{
    use PageFormParams;

    private $page;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpChiefEnvironment();

        app(Register::class)->register(PageManager::class, Single::class);

        $this->page = Single::create();
        $this->page->adoptChild(NewsletterModuleFake::create(['slug' => 'newsletter-one']));
        $this->page->adoptChild(NewsletterModuleFake::create(['slug' => 'newsletter-one', 'owner_id' => $this->page->id, 'owner_type' => $this->page->getMorphClass(), 'dynamic_title' => 'dynamic title']));
        $this->page->adoptChild(ArticlePageFake::create());
    }

    /** @test */
    public function the_pagebuilder_setup_of_an_existing_page_can_be_duplicated()
    {
        $this->disableExceptionHandling();
        $this->asAdmin()->post(route('chief.back.managers.store', 'singles'), array_merge([
            'template' => get_class($this->page).'@'.$this->page->id
        ], $this->validPageParams()));

        $page = Single::find(3);

        $this->assertCount(3, $page->children());
    }
}
