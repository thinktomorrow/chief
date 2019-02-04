<?php

namespace Thinktomorrow\Chief\Tests\Feature\Management;

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

    protected function setUp()
    {
        parent::setUp();

        ManagedModelFake::migrateUp();
        ManagedModelFakeTranslation::migrateUp();

        $this->setUpDefaultAuthorization();

        app(Register::class)->register('fakes', PublishedManagerFake::class, ManagedModelFake::class);

        $this->model = ManagedModelFake::create(['title' => 'Foobar', 'slug' => 'foobar', 'custom_column' => 'custom']);
        $this->fake = (new PublishedManagerFake(app(Register::class)->filterByKey('fakes')->first()))->manage($this->model);

        Route::get('statics/{slug}', function () {
        })->name('pages.show');
    }

    /** @test */
    public function admin_can_publish_a_model()
    {
        $this->asAdmin()
            ->post($this->fake->route('publish'));

        $this->assertTrue($this->model->fresh()->isPublished());
    }

    /** @test */
    public function admin_can_draft_a_model()
    {
        $this->asAdmin()
            ->post($this->fake->route('draft'));

        $this->assertTrue(ManagedModelFake::first()->isDraft());
    }

    /** @test */
    public function guests_cannot_publish_a_model()
    {
        $this->post($this->fake->route('publish'))
            ->assertStatus(302)
            ->assertRedirect(route('chief.back.login'));
    }

    /** @test */
    public function cannot_publish_without_publishable_manager()
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
