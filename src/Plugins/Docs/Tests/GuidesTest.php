<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Docs\Tests;

class GuidesTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->useProjectGuides($this->fixture('project'));
    }

    public function test_a_project_guide_is_shown_at_its_id(): void
    {
        $this->asAdmin()->get('/admin/docs/opleidingen/inschrijvingen')
            ->assertOk()
            ->assertSee('Inschrijvingen beheren')
            ->assertSee('Hier volg je de inschrijvingen op.')
            ->assertDontSee('description:');
    }

    public function test_a_chief_guide_is_shown_at_its_namespaced_id(): void
    {
        $this->asAdmin()->get('/admin/docs/chief::paginas/status')
            ->assertOk()
            ->assertSee('Status: draft, publiceren en archiveren');
    }

    public function test_the_guide_route_generates_readable_urls(): void
    {
        $this->assertSame('http://localhost/admin/docs/chief::paginas/status', route('chief.docs.show', 'chief::paginas/status'));
    }

    public function test_an_index_guide_is_shown_at_its_section_id(): void
    {
        $this->asAdmin()->get('/admin/docs/opleidingen')
            ->assertOk()
            ->assertSee('Een overzicht van alle opleidingen.');

        $this->asAdmin()->get('/admin/docs/opleidingen/index')->assertNotFound();
    }

    public function test_a_section_without_index_guide_has_no_page(): void
    {
        $this->asAdmin()->get('/admin/docs/instellingen-van-de-site')->assertNotFound();
        $this->asAdmin()->get('/admin/docs/instellingen-van-de-site/talen')->assertOk();
    }

    public function test_an_unknown_guide_is_not_found(): void
    {
        $this->asAdmin()->get('/admin/docs/onbestaand')->assertNotFound();
        $this->asAdmin()->get('/admin/docs/chief::onbestaand')->assertNotFound();
    }

    public function test_the_overview_lists_project_and_chief_guides(): void
    {
        $this->asAdmin()->get('/admin/docs')
            ->assertOk()
            ->assertSee('Inschrijvingen beheren')
            ->assertSee('Instellingen Van De Site')
            ->assertSee('Welkom in Chief')
            ->assertSee(route('chief.docs.show', 'chief::paginas/status'));
    }

    public function test_the_overview_creates_no_folder_without_project_guides(): void
    {
        $path = sys_get_temp_dir().'/chief-docs-'.uniqid();
        $this->useProjectGuides($path);

        $this->asAdmin()->get('/admin/docs')
            ->assertOk()
            ->assertSee('Welkom in Chief');

        $this->assertFileDoesNotExist($path);
    }

    public function test_guests_cannot_see_guides(): void
    {
        $this->get('/admin/docs/opleidingen')->assertRedirect();
    }
}
