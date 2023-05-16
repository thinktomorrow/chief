<?php

namespace Thinktomorrow\Chief\Plugins\Tags\Tests\Infrastructure\App\Http;

use Illuminate\Support\Facades\Event;
use Thinktomorrow\Chief\Plugins\Tags\Domain\Events\TagGroupUpdated;
use Thinktomorrow\Chief\Plugins\Tags\Tests\Infrastructure\TestCase;

class EditTagGroupTest extends TestCase
{
    public function test_it_can_visit_the_edit_tag()
    {
        $model = $this->createTagGroupModel();

        $response = $this->asAdmin()->get(route('chief.taggroups.edit', $model->id));

        $response->assertStatus(200);
        $this->assertStringContainsString('action="'.route('chief.taggroups.update', $model->id).'"', $response->getContent());
        $this->assertStringContainsString('value="Review status"', $response->getContent());
    }

    public function test_guests_cannot_view_the_edit_form()
    {
        $model = $this->createTagGroupModel();

        $this->get(route('chief.taggroups.edit', $model->id))
            ->assertStatus(302)
            ->assertRedirect(route('chief.back.login'));
    }

    public function test_it_can_update_a_tag()
    {
        $model = $this->createTagGroupModel();
        $this->performTagGroupUpdate($model->id);

        $model->refresh();

        $this->assertEquals('publication process', $model->label);
    }

    public function test_it_emits_an_tag_updated_event()
    {
        Event::fake();

        $model = $this->createTagGroupModel();
        $this->performTagGroupUpdate($model->id);

        Event::assertDispatched(TagGroupUpdated::class);
    }
}
