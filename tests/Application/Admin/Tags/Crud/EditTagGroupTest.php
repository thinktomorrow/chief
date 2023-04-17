<?php

namespace Thinktomorrow\Chief\Tests\Application\Admin\Tags\Crud;

use Illuminate\Support\Facades\Event;
use Thinktomorrow\Chief\Admin\Tags\Events\TagGroupUpdated;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

class EditTagGroupTest extends ChiefTestCase
{
    use TagTestHelpers;

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
        $this->assertEquals('#666666', $model->color);
    }

    public function test_it_emits_an_tag_updated_event()
    {
        Event::fake();

        $model = $this->createTagGroupModel();
        $this->performTagGroupUpdate($model->id);

        Event::assertDispatched(TagGroupUpdated::class);
    }
}
