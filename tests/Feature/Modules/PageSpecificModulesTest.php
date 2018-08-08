<?php

namespace Thinktomorrow\Chief\Tests\Feature\Modules;

use Thinktomorrow\Chief\Modules\Module;
use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Pages\Single;
use Thinktomorrow\Chief\Tests\ChiefDatabaseTransactions;
use Thinktomorrow\Chief\Tests\Fakes\NewsletterModuleFake;
use Thinktomorrow\Chief\Tests\TestCase;

class PageSpecificModulesTest extends TestCase
{
    use ChiefDatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        $this->app['config']->set('thinktomorrow.chief.collections', [
            'newsletter' => NewsletterModuleFake::class,
            'singles'    => Single::class,
        ]);
    }

    /** @test */
    public function it_can_assign_a_module_to_a_page()
    {
        $page = Page::create(['collection' => 'singles', 'slug' => 'foobar', 'title:nl' => 'foobar']);
        $module = Module::create(['slug' => 'foobar', 'collection' => 'newsletter', 'page_id' => $page->id]);

        $this->assertEquals($page->id, $module->page->id);
        $this->assertTrue($module->isPageSpecific());

        // Module queries
        $this->assertCount(1, Module::all());
        $this->assertCount(0, Module::withoutPageSpecific()->get());

        // Page relations
        $this->assertCount(1, $page->modules()->get());
        $this->assertInstanceOf(NewsletterModuleFake::class, $page->modules->first());
    }
}
