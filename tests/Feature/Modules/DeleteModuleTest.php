<?php

namespace Thinktomorrow\Chief\Tests\Feature\Modules;

use Thinktomorrow\Chief\Common\Relations\Relation;
use Thinktomorrow\Chief\Modules\Module;
use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Tests\Fakes\NewsletterModuleFake;
use Thinktomorrow\Chief\Tests\TestCase;

class DeleteModuleTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->setUpDefaultAuthorization();

        $this->app['config']->set('thinktomorrow.chief.collections', [
            'newsletter' => NewsletterModuleFake::class,
        ]);
    }

    /** @test */
    public function it_can_delete_modules()
    {
        $module = Module::create(['collection' => 'newsletter', 'slug' => 'other-slug']);

        $this->asAdmin()
            ->delete(route('chief.back.modules.destroy', $module->id));

        $this->assertCount(0, Module::all());
        $this->assertCount(1, Module::onlyTrashed()->get());
    }

    /** @test */
    public function it_also_deletes_module_relations()
    {
        $this->disableExceptionHandling();

        $page = factory(Page::class)->create();

        $module = Module::create(['collection' => 'newsletter', 'slug' => 'other-slug']);
        $page->adoptChild($module);

        $this->assertEquals(1, Relation::count());

        $this->asAdmin()
            ->delete(route('chief.back.modules.destroy', $module->id));

        $this->assertCount(0, Module::all());
        $this->assertEquals(0, Relation::count());
    }
}
