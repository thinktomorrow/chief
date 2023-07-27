<?php

namespace Thinktomorrow\Chief\Tests\Application\Site;

use Thinktomorrow\Chief\ManagedModels\States\PageState\PageState;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

class InternalLinksTest extends ChiefTestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function it_retrieves_empty_response_when_no_links_found()
    {
        $response = $this->asAdmin()->get(route('chief.api.internal-links'));
        $response->assertSuccessful();

        $response->assertJson([
            ['name' => '...', 'url' => ''],
        ]);
    }

    /** @test */
    public function it_can_retrieve_links_of_online_models()
    {
        $article = $this->setupAndCreateArticle(['title' => 'foobar', 'current_state' => PageState::published]);
        $this->updateLinks($article, ['nl' => 'foobar-nl', 'en' => 'foobar-en']);

        $response = $this->asAdmin()->get(route('chief.api.internal-links'));
        $response->assertSuccessful();

        $response->assertJson([
            ['name' => '...', 'url' => ''],
            ['name' => 'foobar', 'url' => 'http://localhost/foobar-nl'],
        ]);
    }

    /** @test */
    public function it_can_retrieve_links_of_online_models_for_specific_locale()
    {
        $article = $this->setupAndCreateArticle(['title' => 'foobar', 'current_state' => PageState::published]);
        $this->updateLinks($article, ['nl' => 'foobar-nl', 'en' => 'foobar-en']);

        $response = $this->asAdmin()->get(route('chief.api.internal-links') . '?locale=en');
        $response->assertJson([
            ['name' => '...', 'url' => ''],
            ['name' => 'foobar', 'url' => 'http://localhost/foobar-en'],
        ]);
    }
}
