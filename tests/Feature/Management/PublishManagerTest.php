<?php

namespace Thinktomorrow\Chief\Tests\Feature\Management;

use Illuminate\Support\Facades\Route;
use Thinktomorrow\Chief\Management\Exceptions\MissingAssistant;
use Thinktomorrow\Chief\Management\Register;
use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagedModelFake;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagedModelFakeTranslation;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagerFake;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\PublishedManagerFake;
use Thinktomorrow\Chief\Tests\TestCase;

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
            ->post($this->fake->assistant('publish')->route('publish'));

        $this->assertCount(1, Page::published()->get());
    }

    /** @test */
    public function admin_can_draft_a_model()
    {
        $page = Page::first();
        $page->publish();

        $this->assertCount(1, Page::published()->get());

        $this->asAdmin()
            ->post($this->fake->assistant('publish')->route('draft'));

        $this->assertTrue(Page::first()->isDraft());
    }

    /** @test */
    public function guests_cannot_publish_a_model()
    {
        $this->post($this->fake->assistant('publish')->route('publish'))
            ->assertStatus(302)
            ->assertRedirect(route('chief.back.login'));
    }

    /** @test */
    public function cannot_publish_without_publish_assistant()
    {
        $this->disableExceptionHandling();
        $this->expectException(MissingAssistant::class);

        app(Register::class)->register('publishfakes', ManagerFake::class, ManagedModelFake::class);

        $this->model = ManagedModelFake::create(['title' => 'Foobar', 'slug' => 'foobar', 'custom_column' => 'custom']);
        $this->fake = (new ManagerFake(app(Register::class)->filterByKey('publishfakes')->first()))->manage($this->model);

        $this->asAdmin()
            ->post($this->fake->assistant('publish')->route('publish'));
    }
}
