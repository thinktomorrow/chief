<?php

namespace Thinktomorrow\Chief\Tests\Application\Admin\Tags\Crud;

use Illuminate\Support\Facades\Event;
use Thinktomorrow\Chief\Admin\Tags\Events\TagUpdated;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

class EditTagTest extends ChiefTestCase
{
    use TagTestHelpers;

    public function test_it_can_visit_the_edit_tag()
    {
        $model = $this->createTagModel();

        $response = $this->asAdmin()->get(route('chief.tags.edit', $model->id));

        $response->assertStatus(200);
        $this->assertStringContainsString('action="'.route('chief.tags.update', $model->id).'"', $response->getContent());
        $this->assertStringContainsString('value="in review"', $response->getContent());
    }

    public function test_guests_cannot_view_the_edit_form()
    {
        $model = $this->createTagModel();

        $this->get(route('chief.tags.edit', $model->id))
            ->assertStatus(302)
            ->assertRedirect(route('chief.back.login'));
    }

    public function test_it_can_update_a_tag()
    {
        $model = $this->createTagModel();
        $this->performTagUpdate($model->id);

        $model->refresh();

        $this->assertEquals('reviewed', $model->label);
        $this->assertEquals('2', $model->taggroup_id);
        $this->assertEquals('#666666', $model->color);
    }

    public function test_it_emits_an_tag_updated_event()
    {
        Event::fake();

        $model = $this->createTagModel();
        $this->performTagUpdate($model->id);

        Event::assertDispatched(TagUpdated::class);
    }
}
