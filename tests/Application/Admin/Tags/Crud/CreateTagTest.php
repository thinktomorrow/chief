<?php

namespace Thinktomorrow\Chief\Tests\Application\Admin\Tags\Crud;

use Illuminate\Support\Facades\Event;
use Thinktomorrow\Chief\Admin\Tags\Events\TagCreated;
use Thinktomorrow\Chief\Admin\Tags\TagModel;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

class CreateTagTest extends ChiefTestCase
{
    use TagTestHelpers;

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
        $this->performTagStore();

        $this->assertEquals(1, TagModel::count());

        $tag = TagModel::first();
        $this->assertEquals('reviewing', $tag->label);
        $this->assertEquals('1', $tag->taggroup_id);
        $this->assertEquals('#333333', $tag->color);
    }

    public function test_it_can_create_a_tag_without_taggroup()
    {
        $this->performTagStore(['taggroup_id' => null]);

        $tag = TagModel::first();
        $this->assertNull($tag->taggroup_id);
    }

    public function test_it_can_validate_input()
    {
        $response = $this->performTagStore(['label' => null]);

        $response->assertSessionHasErrors('label')
                 ->assertStatus(302);

        $this->assertEquals(0, TagModel::count());
    }

    public function test_it_emits_an_tag_created_event()
    {
        Event::fake();

        $this->performTagStore();

        Event::assertDispatched(TagCreated::class);
    }
}
