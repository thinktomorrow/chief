<?php

namespace Thinktomorrow\Chief\Plugins\TimeTable\Tests\Infrastructure\Admin\Http;

use Illuminate\Support\Facades\Event;
use Thinktomorrow\Chief\Plugins\TimeTable\Domain\Events\DateCreated;
use Thinktomorrow\Chief\Plugins\TimeTable\Infrastructure\Models\DateModel;
use Thinktomorrow\Chief\Plugins\TimeTable\Tests\Infrastructure\TestCase;

class CreateTagTest extends TestCase
{
    public function test_it_can_visit_the_create_tag()
    {
        $response = $this->asAdmin()->get(route('chief.tags.create'));

        $response->assertStatus(200);
        $this->assertStringContainsString('action="'.route('chief.tags.store').'"', $response->getContent());
    }

    public function test_guests_cannot_view_the_create_form()
    {
        $this->get(route('chief.tags.create'))
            ->assertStatus(302)
            ->assertRedirect(route('chief.back.login'));
    }

    public function test_it_can_create_a_tag()
    {
        // Provide taggroup so tag can have a taggroup associated.
        $this->createTimeTableModel();
        $this->performDateStore();

        $this->assertEquals(1, DateModel::count());

        $tag = DateModel::first();

        $this->assertEquals('reviewing', $tag->label);
        $this->assertEquals('1', $tag->taggroup_id);
        $this->assertEquals('#333333', $tag->color);
    }

    public function test_it_can_create_a_tag_without_taggroup()
    {
        $this->performDateStore(['taggroup_id' => null]);

        $tag = DateModel::first();
        $this->assertNull($tag->taggroup_id);
    }

    public function test_it_can_validate_input()
    {
        $response = $this->performDateStore(['label' => null]);

        $response->assertSessionHasErrors('label')
                 ->assertStatus(302);

        $this->assertEquals(0, DateModel::count());
    }

    public function test_it_emits_an_tag_created_event()
    {
        Event::fake();

        $this->performDateStore();

        Event::assertDispatched(DateCreated::class);
    }
}
