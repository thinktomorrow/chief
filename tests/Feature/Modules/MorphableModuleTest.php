<?php

namespace Thinktomorrow\Chief\Tests\Feature\Modules;

use Illuminate\Database\Eloquent\Relations\Relation;
use Thinktomorrow\Chief\Modules\Module;
use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\Tests\Fakes\OtherModuleFake;
use Thinktomorrow\Chief\Tests\Fakes\NewsletterModuleFake;

class MorphableModuleTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function tearDown()
    {
        // Reset relation mapping
        Relation::$morphMap = [];

        parent::tearDown();
    }

    /** @test */
    public function a_module_can_be_divided_by_morph_key()
    {
        Module::create(['morph_key' => NewsletterModuleFake::class, 'slug' => 'foobar']);

        $this->assertCount(1, NewsletterModuleFake::all());
        $this->assertCount(0, OtherModuleFake::all());

        $this->assertCount(0, Module::morphable(null)->get());
        $this->assertCount(1, Module::all()); // Base class ignores collection (because no collection key ignores scope)
        $this->assertCount(1, Module::all());
    }

    /** @test */
    public function a_module_can_be_retrieved_by_morph_key()
    {
        Module::create(['morph_key' => NewsletterModuleFake::class, 'slug' => 'foobar']);

        $this->assertNotNull(NewsletterModuleFake::first());
        $this->assertNull(OtherModuleFake::first());

        $this->assertNotNull(Module::morphable(NewsletterModuleFake::class)->first());
        $this->assertNull(Module::morphable(null)->first());
    }

    /** @test */
    public function generic_module_class_always_ignores_the_morph_key()
    {
        Module::create(['morph_key' => NewsletterModuleFake::class, 'slug' => 'foobar']);

        $this->assertNotNull(Module::first());
    }

    /** @test */
    public function morph_key_utilises_the_laravel_morph_map()
    {
        Relation::morphMap(['newsletter' => NewsletterModuleFake::class]);
        Module::create(['morph_key' => 'newsletter', 'slug' => 'foobar']);

        $this->assertCount(1, Module::morphable('newsletter')->get());
    }

    /** @test */
    public function it_can_create_instance_from_flat_reference()
    {
        $module = NewsletterModuleFake::create(['slug' => 'foobar']);

        $instance = $module->flatReference()->instance();

        $this->assertInstanceOf(NewsletterModuleFake::class, $instance);
        $this->assertEquals($module->id, $instance->id);
    }

    /** @test */
    public function morph_key_can_be_scoped_on_runtime()
    {
        Module::create(['morph_key' => NewsletterModuleFake::class, 'slug' => 'foobar']);

        $this->assertNotNull(Module::morphable(NewsletterModuleFake::class)->first());
        $this->assertNull(Module::morphable('others')->first());
        $this->assertNull(Module::morphable('hero')->first());
        $this->assertNull(Module::morphable(null)->first());
    }

    /** @test */
    public function it_returns_the_right_morphable_with_the_eloquent_find_methods()
    {
        $module = NewsletterModuleFake::create(['slug' => 'foobar',]);

        $this->assertInstanceOf(NewsletterModuleFake::class, Module::find($module->id));
        $this->assertInstanceOf(NewsletterModuleFake::class, Module::findOrFail($module->id));
    }

    /** @test */
    public function it_returns_the_right_morphable_model_by_slug()
    {
        NewsletterModuleFake::create(['slug' => 'foobar']);

        $this->assertInstanceOf(NewsletterModuleFake::class, Module::findBySlug('foobar'));
    }
}
