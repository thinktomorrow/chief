<?php

namespace Thinktomorrow\Chief\Plugins\WeekTable\Tests\Infrastructure\Admin\Http;

use Thinktomorrow\Chief\Plugins\WeekTable\Tests\Infrastructure\TestCase;

class IndexTagTest extends TestCase
{
    public function test_it_can_visit_the_index()
    {
        $model = $this->createTagModel();

        $response = $this->asAdmin()->get(route('chief.tags.index'));

        $response->assertStatus(200);
    }

    public function test_guests_cannot_view_the_index_form()
    {
        $model = $this->createTagModel();

        $this->get(route('chief.tags.index'))
            ->assertStatus(302)
            ->assertRedirect(route('chief.back.login'));
    }
}
