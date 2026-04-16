<?php

namespace Thinktomorrow\Chief\Tests\Application\Admin;

use Thinktomorrow\Chief\Tests\ChiefTestCase;

class NotFoundAdminResponseTest extends ChiefTestCase
{
    private string $missingEditUrl;

    public function test_it_renders_a_chief_not_found_page_for_missing_model_on_admin_edit_route()
    {
        $response = $this->asAdmin()->get($this->missingEditUrl);

        $response->assertStatus(404);
        $response->assertSee('Pagina niet gevonden');
        $response->assertSee('Naar het dashboard');
    }

    protected function setUp(): void
    {
        parent::setUp();

        $model = $this->setUpAndCreateArticle([
            'title' => 'First article',
        ]);

        $this->missingEditUrl = $this->manager($model)->route('edit', 'non-existing-model-id');
    }
}
