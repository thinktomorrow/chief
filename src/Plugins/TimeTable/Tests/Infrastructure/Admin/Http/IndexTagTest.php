<?php

namespace Thinktomorrow\Chief\Plugins\TimeTable\Tests\Infrastructure\App\Http;

use Thinktomorrow\Chief\Plugins\TimeTable\Tests\Infrastructure\TestCase;

class IndexTagTest extends TestCase
{
    public function test_it_can_visit_the_index()
    {
        $model = $this->createDateModel();

        $response = $this->asAdmin()->get(route('chief.tags.index'));

        $response->assertStatus(200);
    }

    public function test_guests_cannot_view_the_index_form()
    {
        $model = $this->createDateModel();

        $this->get(route('chief.tags.index'))
            ->assertStatus(302)
            ->assertRedirect(route('chief.back.login'));
    }
}
