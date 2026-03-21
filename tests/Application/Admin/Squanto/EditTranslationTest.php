<?php

namespace Thinktomorrow\Chief\Tests\Application\Admin\Squanto;

use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Squanto\Database\DatabaseLine;

class EditTranslationTest extends ChiefTestCase
{
    public function test_admin_can_view_the_edit_form()
    {
        $response = $this->asAdmin()->get(route('squanto.edit', 'home'));
        $response->assertViewIs('squanto::edit')
            ->assertStatus(200);
    }

    public function test_guests_cannot_view_the_edit_form()
    {
        $response = $this->get(route('squanto.edit', 'home'));
        $response->assertStatus(302)->assertRedirect(route('chief.back.login'));
    }

    public function test_edit_view_contains_anchor_targets_for_lines(): void
    {
        DatabaseLine::create([
            'key' => 'home.hero.title',
            'values' => ['value' => [
                'nl' => 'Welkom',
            ]],
            'metadata' => ['label' => 'Titel'],
        ]);

        $response = $this->asAdmin()->get(route('squanto.edit', 'home'));

        $response->assertStatus(200)
            ->assertSee('id="homeherotitle"', false);
    }
}
