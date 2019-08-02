<?php

namespace Thinktomorrow\Chief\Tests\Feature\Modules;

use Thinktomorrow\Chief\Modules\Module;
use Thinktomorrow\Chief\Pages\Single;
use Thinktomorrow\Chief\Tests\ChiefDatabaseTransactions;
use Thinktomorrow\Chief\Tests\TestCase;

class ModuleTest extends TestCase
{
    use ChiefDatabaseTransactions;

    /** @test */
    public function it_can_find_a_module_by_slug()
    {
        $module = Module::create(['internal_title' => 'foobar']);

        $this->assertEquals($module->id, Module::findByInternalTitle('foobar')->id);
    }
}
