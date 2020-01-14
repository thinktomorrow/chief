<?php

namespace Thinktomorrow\Chief\Tests\Feature\Modules;

use Thinktomorrow\Chief\Management\Register;
use Thinktomorrow\Chief\Modules\ModuleManager;
use Thinktomorrow\Chief\Relations\Relation;
use Thinktomorrow\Chief\Modules\Module;
use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Tests\Fakes\NewsletterModuleFake;
use Thinktomorrow\Chief\Tests\TestCase;

class DeleteModuleTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpDefaultAuthorization();

        app(Register::class)->register(ModuleManager::class, NewsletterModuleFake::class);
    }

    /** @test */
    public function it_can_delete_modules()
    {
        $module = NewsletterModuleFake::create(['slug' => 'other-slug']);

        $this->asAdmin()
            ->delete(route('chief.back.managers.delete', ['newsletters_fake', $module->id]), [
                'deleteconfirmation' => 'DELETE',
            ]);

        $this->assertCount(0, Module::all());
        $this->assertCount(1, Module::onlyTrashed()->get());
        $this->assertStringStartsWith('other-slug_DELETED_', Module::onlyTrashed()->first()->slug);
    }

    /** @test */
    public function it_also_deletes_module_relations()
    {
        $page = factory(Page::class)->create();

        $module = NewsletterModuleFake::create(['slug' => 'other-slug']);
        $page->adoptChild($module);

        $this->assertEquals(1, Relation::count());

        $this->asAdmin()
            ->delete(route('chief.back.managers.delete', ['newsletters_fake', $module->id]), [
            'deleteconfirmation' => 'DELETE',
        ]);

        $this->assertCount(0, Module::all());
        $this->assertEquals(0, Relation::count());
    }
}
