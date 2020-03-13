<?php

namespace Thinktomorrow\Chief\Tests\Unit\DynamicAttributes;

use Thinktomorrow\Chief\Tests\TestCase;

class LocalizedDynamicAttributesTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        ModelStub::migrateUp();
    }

    /** @test */
    public function it_can_get_a_localized_dynamic_attribute()
    {
        $model = new ModelStub(['values' => [
            'nl' => ['title' => 'localized title nl'],
            'en' => ['title' => 'localized title en'],
        ]]);

        app()->setLocale('nl');
        $this->assertEquals('localized title nl', $model->title);

        app()->setLocale('en');
        $this->assertEquals('localized title en', $model->title);

        $this->assertEquals('localized title en', $model->dynamic('title', 'en'));
        $this->assertEquals('localized title nl', $model->dynamic('title', 'nl'));
    }

    /** @test */
    public function it_does_not_provide_a_fallback_localized_value()
    {
        $model = new ModelStub(['values' => [
            'nl' => ['title' => 'localized title nl'],
        ]]);

        app()->setLocale('en');
        $this->assertNull($model->title);
    }

    /** @test */
    public function it_can_save_a_localized_dynamic_attribute()
    {
        $model = new ModelStub(['values' => []]);
        $model->setDynamic('title', 'title value nl', 'nl');
        $model->setDynamic('title', 'title value en', 'en');
        $model->save();

        $model = ModelStub::first();

        $this->assertEquals('title value nl', $model->dynamic('title', 'nl'));
        $this->assertEquals('title value en', $model->dynamic('title', 'en'));
        $this->assertEquals('title value nl', $model->title); // app locale is nl
    }
}
