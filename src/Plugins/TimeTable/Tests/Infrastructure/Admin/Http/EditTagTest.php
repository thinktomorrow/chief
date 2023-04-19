<?php

namespace Thinktomorrow\Chief\Plugins\TimeTable\Tests\Infrastructure\Admin\Http;

use Illuminate\Support\Facades\Event;
use Thinktomorrow\Chief\Plugins\TimeTable\Domain\Events\DateUpdated;
use Thinktomorrow\Chief\Plugins\TimeTable\Tests\Infrastructure\TestCase;

class EditTagTest extends TestCase
{
    public function test_it_can_visit_the_edit_tag()
    {
        $model = $this->createDateModel();

        $response = $this->asAdmin()->get(route('chief.tags.edit', $model->id));

        $response->assertStatus(200);
        $this->assertStringContainsString('action="'.route('chief.tags.update', $model->id).'"', $response->getContent());
        $this->assertStringContainsString('value="in review"', $response->getContent());
    }

    public function test_guests_cannot_view_the_edit_form()
    {
        $model = $this->createDateModel();

        $this->get(route('chief.tags.edit', $model->id))
            ->assertStatus(302)
            ->assertRedirect(route('chief.back.login'));
    }

    public function test_it_can_update_a_tag()
    {
        $this->createTimeTableModel();
        $model = $this->createDateModel();
        $this->performDateUpdate($model->id);

        $model->refresh();

        $this->assertEquals('reviewed', $model->label);
        $this->assertEquals('2', $model->taggroup_id);
        $this->assertEquals('#666666', $model->color);
    }

    public function test_it_emits_an_tag_updated_event()
    {
        Event::fake();

        $model = $this->createDateModel();
        $this->performDateUpdate($model->id);

        Event::assertDispatched(DateUpdated::class);
    }
}
