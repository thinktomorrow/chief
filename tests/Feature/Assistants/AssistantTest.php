<?php

namespace Thinktomorrow\Chief\Tests\Feature\Assistants;

use Illuminate\Support\Arr;
use Illuminate\Routing\Route;
use Illuminate\Routing\Router;
use Thinktomorrow\Chief\Tests\TestCase;
use Illuminate\Routing\RouteCollection;
use Thinktomorrow\Chief\Management\Register;
use Thinktomorrow\Chief\Management\Registration;
use Thinktomorrow\Chief\Tests\Fakes\ArticlePageFake;
use Thinktomorrow\Chief\Tests\Fakes\ArticlePageManager;
use Thinktomorrow\Chief\Tests\Feature\Assistants\Stubs\FavoriteAssistant;

class AssistantTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpDefaultAuthorization();

//        $this->page = factory(Page::class)->create();
//        $this->fake = (new PublishedManagerFake(app(Register::class)->filterByKey('singles')->first()))->manage($this->page);
//
//        Route::get('statics/{slug}', function () {
//        })->name('pages.show');
    }

    /** @test */
    function a_custom_admin_filepath_can_add_chief_admin_routes()
    {
        // There is a dummy.route route defined in the config test stub
        $this->assertStringEndsWith('/admin/dummy-route', route('dummy.route'));

        /** @var Route $registeredDummyRoute */
        $registeredDummyRoute = Arr::get(app('router')->getRoutes()->get('GET'), 'admin/dummy-route');

        $this->assertEquals(['web','web-chief','auth:chief'], $registeredDummyRoute->gatherMiddleware());
    }

    /** @test */
    function an_assistant_route_can_only_be_requested_when_authenticated()
    {
        $manager = $this->setupManager();

        $route = $manager->assistant('favorite')->route('dummy-favorite');

        $response = $this->post($route);

        $response->assertStatus(302)
                 ->assertRedirect(route('chief.back.login'));
    }

    /** @test */
    function an_assistant_route_is_always_registered_under_chief_middleware()
    {
        $this->disableExceptionHandling();
        $manager = $this->setupManager();

        $route = $manager->assistant('favorite')->route('dummy-favorite');

        $response = $this->asAdmin()->post($route);

//        $this->assertEquals()
    }

    /** @test */
    function it_can_handle_an_assistant_specific_request()
    {
        // TEMP
        FavoriteAssistant::registerRoutes();

        $article = ArticlePageFake::create();
        $manager = (new ArticlePageManager(new Registration(ArticlePageManager::class, ArticlePageFake::class)))->manage($article);

        $manager->addAssistant(FavoriteAssistant::class);

        $route = $manager->assistant('favorite')->route('favorite');
        trap($route);
        trap($manager->assistants());
    }

    /**
     * @return \Thinktomorrow\Chief\Management\Manager
     */
    private function setupManager(): \Thinktomorrow\Chief\Management\Manager
    {
        app(Register::class)->register(ArticlePageManager::class, ArticlePageFake::class, []);

        $article = ArticlePageFake::create();
        $manager = (new ArticlePageManager(new Registration(ArticlePageManager::class, ArticlePageFake::class)))->manage($article);
        $manager->addAssistant(FavoriteAssistant::class);

        return $manager;
    }

}
