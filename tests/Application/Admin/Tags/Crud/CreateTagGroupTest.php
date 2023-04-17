<?php

namespace Thinktomorrow\Chief\Tests\Application\Admin\Tags\Crud;

use Illuminate\Support\Facades\Event;
use Thinktomorrow\Chief\Admin\Tags\Events\TagGroupCreated;
use Thinktomorrow\Chief\Admin\Tags\TagGroupModel;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

class CreateTagGroupTest extends ChiefTestCase
{
    use TagTestHelpers;

    public function test_it_can_visit_the_create_taggroup()
    {
        $response = $this->asAdmin()->get(route('chief.taggroups.create'));

        $response->assertStatus(200);
        $this->assertStringContainsString('action="'.route('chief.taggroups.store').'"', $response->getContent());
    }

    public function test_guests_cannot_view_the_create_form()
    {
        $this->get(route('chief.taggroups.create'))
            ->assertStatus(302)
            ->assertRedirect(route('chief.back.login'));
    }

    public function test_it_can_create_a_tag()
    {
        $this->performTagGroupStore();

        $this->assertEquals(1, TagGroupModel::count());

        $tag = TagGroupModel::first();
        $this->assertEquals('review states', $tag->label);
        $this->assertEquals('#333333', $tag->color);
    }

    public function test_it_can_validate_input()
    {
        $response = $this->performTagGroupStore(['label' => null]);

        $response->assertSessionHasErrors('label')
            ->assertStatus(302);

        $this->assertEquals(0, TagGroupModel::count());
    }

    public function test_it_emits_an_tag_created_event()
    {
        Event::fake();

        $this->performTagGroupStore();

        Event::assertDispatched(TagGroupCreated::class);
    }
}
