<?php

namespace Thinktomorrow\Chief\Tests\Feature\Modules;

use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Modules\Module;
use Thinktomorrow\Chief\Tests\TestCase;
use Illuminate\Database\Eloquent\Relations\Relation;
use Thinktomorrow\Chief\Tests\ChiefDatabaseTransactions;
use Thinktomorrow\Chief\Tests\Fakes\NewsletterModuleFake;
use Thinktomorrow\Chief\Tests\Fakes\CustomTableArticleFake;

class PageSpecificModulesTest extends TestCase
{
    use ChiefDatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        CustomTableArticleFake::migrateUp();

        Relation::morphMap([
            'customArticles' => CustomTableArticleFake::class,
        ]);
    }

    /** @test */
    public function it_can_assign_a_module_to_a_page()
    {
        $page = Page::create(['morph_key' => 'singles', 'title:nl' => 'foobar']);
        $module = Module::create(['morph_key' => NewsletterModuleFake::class, 'slug' => 'foobar', 'page_morph_key' => $page->morphKey(), 'page_id' => $page->id]);

        $this->assertEquals($page->id, $module->page->id);
        $this->assertTrue($module->isPageSpecific());

        // Module queries
        $this->assertCount(1, Module::all());
        $this->assertCount(0, Module::withoutPageSpecific()->get());

        // Page relations
        $this->assertCount(1, $page->modules()->get());
        $this->assertInstanceOf(NewsletterModuleFake::class, $page->modules->first());
    }

    /** @test */
    public function module_can_be_linked_to_a_detached_page()
    {
        $created = new CustomTableArticleFake();
        $created->save();
        $module = Module::create(['morph_key' => NewsletterModuleFake::class, 'slug' => 'foobar', 'page_morph_key' => $created->getMorphClass(), 'page_id' => $created->id]);

        $this->assertEquals($created->id, $module->page->id);
    }
}
