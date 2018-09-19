<?php

namespace Thinktomorrow\Chief\Tests\Feature\Modules;

use Thinktomorrow\Chief\Modules\Module;
use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Pages\Single;
use Thinktomorrow\Chief\Tests\ChiefDatabaseTransactions;
use Thinktomorrow\Chief\Tests\TestCase;

class ModuleTest extends TestCase
{
    use ChiefDatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        $this->app['config']->set('thinktomorrow.chief.collections', [
            'module-collection' => Module::class,
            'singles' => Single::class,
        ]);
    }

    /** @test */
    public function it_can_find_a_module_by_slug()
    {
        $module = Module::create(['slug' => 'foobar', 'collection' => 'module-collection']);

        $this->assertEquals($module->id, Module::findBySlug('foobar')->id);
    }
}
