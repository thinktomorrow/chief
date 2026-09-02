<?php

namespace Thinktomorrow\Chief\Tests\Application\Admin\Squanto;

use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Squanto\Database\DatabaseLine;
use Thinktomorrow\Squanto\Manager\Pages\LineViewModel;

class EditTranslationTest extends ChiefTestCase
{
    use InteractsWithSquantoSources;

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

    public function test_edit_view_uses_section_label_from_line_metadata(): void
    {
        if (! method_exists(LineViewModel::class, 'sectionLabel')) {
            $this->markTestSkipped('Requires Squanto section label support.');
        }

        DatabaseLine::create([
            'key' => 'mails.registration-rejected.content',
            'values' => ['value' => [
                'nl' => 'Je inschrijving is afgewezen.',
            ]],
        ]);

        DatabaseLine::create([
            'key' => 'mails.registration-rejected.subject',
            'values' => ['value' => [
                'nl' => 'Afwijzing inschrijving',
            ]],
            'metadata' => ['section_label' => 'E-mail afwijzing inschrijving'],
        ]);

        $response = $this->asAdmin()->get(route('squanto.edit', 'mails'));

        $response->assertStatus(200)
            ->assertSee('E-mail afwijzing inschrijving');
    }

    public function test_admin_can_view_the_edit_form_for_a_namespaced_page_slug(): void
    {
        $this->skipWithoutNamespacedSquantoSupport();

        $this->registerPluginSource();

        DatabaseLine::create([
            'key' => 'chief-form-plugin::general.title',
            'values' => ['value' => [
                'nl' => 'Plugin title',
            ]],
        ]);

        $response = $this->asAdmin()->get(route('squanto.edit', 'chief-form-plugin::general'));

        $response->assertViewIs('squanto::edit')
            ->assertStatus(200)
            ->assertSee('id="chief-form-plugingeneraltitle"', false);
    }
}
