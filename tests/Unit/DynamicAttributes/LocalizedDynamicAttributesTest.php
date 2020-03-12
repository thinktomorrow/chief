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
    function it_can_get_a_localized_dynamic_attribute()
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
    function it_does_not_provide_a_fallback_localized_value()
    {
        $model = new ModelStub(['values' => [
            'nl' => ['title' => 'localized title nl'],
        ]]);

        app()->setLocale('en');
        $this->assertNull($model->title);
    }

    /** @test */
    function it_can_be_used_along_the_translatable_trait()
    {

    }

    /** @test */
    function it_can_set_a_localized_dynamic_attribute()
    {

    }
}
