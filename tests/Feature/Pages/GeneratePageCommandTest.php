<?php

namespace Thinktomorrow\Chief\Tests\Feature\Pages;

use Thinktomorrow\Chief\Pages\Console\GeneratePage;
use Thinktomorrow\Chief\Tests\TestCase;

class GeneratePageCommandTest extends TestCase
{
    private $command;

    protected function setUp()
    {
        parent::setUp();

        $temp_folder = __DIR__.'/../../tmp';

        $this->command = \Mockery::mock(GeneratePage::class.'[choice]', [$this->app['files'], [
            'base_path' => $temp_folder,
        ]]);

        $this->app->bind('command.chief:page', function () {
            return $this->command;
        });
    }

    /** @test */
    function it_requires_a_name_parameter()
    {
        $this->expectException(\RuntimeException::class);

        $this->artisan('chief:page');
    }

    /** @test */
    function a_model_can_be_generated()
    {
        $this->markTestIncomplete();

        $this->command->shouldReceive('choice')
                      ->once()
                      ->andReturn(0);

        $this->artisan('chief:page', [
            'name' => 'article',
        ]);
    }
}