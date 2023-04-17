<?php

namespace Thinktomorrow\Chief\Tests\Application\Admin\Tags\Crud;

use Illuminate\Support\Facades\Event;
use Thinktomorrow\Chief\Admin\Tags\Events\TagUpdated;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

class IndexTagTest extends ChiefTestCase
{
    use TagTestHelpers;

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
