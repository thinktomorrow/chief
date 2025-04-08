<?php

namespace Thinktomorrow\Chief\Urls\Tests\App;

use Thinktomorrow\Chief\ManagedModels\States\PageState\PageState;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

class InternalLinksTest extends ChiefTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_it_retrieves_empty_response_when_no_links_found()
    {
        $response = $this->asAdmin()->get(route('chief.api.internal-links'));
        $response->assertSuccessful();

        $response->assertJson([
            ['name' => '...', 'url' => ''],
        ]);
    }

    public function test_it_can_retrieve_links_of_online_models()
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

    public function test_it_can_retrieve_links_of_online_models_for_specific_locale()
    {
        $article = $this->setupAndCreateArticle(['title' => 'foobar', 'current_state' => PageState::published]);
        $this->updateLinks($article, ['nl' => 'foobar-nl', 'en' => 'foobar-en']);

        $response = $this->asAdmin()->get(route('chief.api.internal-links').'?locale=en');
        $response->assertJson([
            ['name' => '...', 'url' => ''],
            ['name' => 'foobar', 'url' => 'http://localhost/foobar-en'],
        ]);
    }
}
