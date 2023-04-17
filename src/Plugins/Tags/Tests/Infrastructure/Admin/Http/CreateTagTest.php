<?php

namespace Thinktomorrow\Chief\Plugins\Tags\Tests\Infrastructure\Admin\Http;

use Illuminate\Support\Facades\Event;
use Thinktomorrow\Chief\Plugins\Tags\Domain\Events\TagCreated;
use Thinktomorrow\Chief\Plugins\Tags\Domain\Model\TagModel;
use Thinktomorrow\Chief\Plugins\Tags\Tests\Infrastructure\TestCase;

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
        $this->createTaggroupModel();
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
