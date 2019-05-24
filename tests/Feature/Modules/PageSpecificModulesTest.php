<?php

namespace Thinktomorrow\Chief\Tests\Feature\Modules;

use Thinktomorrow\Chief\Modules\Module;
use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Tests\ChiefDatabaseTransactions;
use Thinktomorrow\Chief\Tests\Fakes\NewsletterModuleFake;
use Thinktomorrow\Chief\Tests\TestCase;

class PageSpecificModulesTest extends TestCase
{
    use ChiefDatabaseTransactions;

    /** @test */
    public function it_can_assign_a_module_to_a_page()
    {
        $page = Page::create(['morph_key' => 'singles', 'slug' => 'foobar', 'title:nl' => 'foobar']);
        $module = Module::create(['morph_key' => NewsletterModuleFake::class, 'slug' => 'foobar', 'page_id' => $page->id]);

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
