<?php

namespace Thinktomorrow\Chief\Tests\Feature\Management;

use Thinktomorrow\Chief\Pages\Page;
use Illuminate\Support\Facades\Route;
use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\Management\Register;
use Thinktomorrow\Chief\Management\NotAllowedManagerRoute;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagerFake;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagedModelFake;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\PublishedManagerFake;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagedModelFakeTranslation;

class PublishManagerTest extends TestCase
{
    private $fake;

    protected function setUp(): void
    {
        parent::setUp();

        ManagedModelFake::migrateUp();
        ManagedModelFakeTranslation::migrateUp();

        $this->setUpDefaultAuthorization();

        $this->page = factory(Page::class)->create(['published' => false]);
        $this->fake = (new PublishedManagerFake(app(Register::class)->filterByKey('singles')->first()))->manage($this->page);

        Route::get('statics/{slug}', function () {
        })->name('pages.show');
    }

    /** @test */
    public function admin_can_publish_a_model()
    {
        $this->assertCount(0, Page::published()->get());

        $this->asAdmin()
            ->post($this->fake->route('publish'));

        $this->assertCount(1, Page::published()->get());
    }

    /** @test */
    public function admin_can_draft_a_model()
    {
        $this->asAdmin()
            ->post($this->fake->route('draft'));

        $this->assertTrue(Page::first()->isDraft());
    }

    /** @test */
    public function guests_cannot_publish_a_model()
    {
        $this->post($this->fake->route('publish'))
            ->assertStatus(302)
            ->assertRedirect(route('chief.back.login'));
    }

    /** @test */
    public function cannot_publish_without_publish_assistant()
    {
        $this->disableExceptionHandling();
        $this->expectException(NotAllowedManagerRoute::class);

        app(Register::class)->register('publishfakes', ManagerFake::class, ManagedModelFake::class);

        $this->model = ManagedModelFake::create(['title' => 'Foobar', 'slug' => 'foobar', 'custom_column' => 'custom']);
        $this->fake = (new ManagerFake(app(Register::class)->filterByKey('publishfakes')->first()))->manage($this->model);

        $this->asAdmin()
            ->post($this->fake->route('publish'));
    }
}
