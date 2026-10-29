<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Docs\Tests;

class GuideLinksTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->useProjectGuides($this->fixture('links'));
    }

    public function test_a_relative_link_resolves_to_a_guide_of_the_same_source(): void
    {
        $this->asAdmin()->get('/admin/docs/opleidingen')
            ->assertOk()
            ->assertSee($this->guideLink('opleidingen/inschrijvingen', 'Inschrijven'), false);
    }

    public function test_a_relative_link_can_leave_its_folder(): void
    {
        $this->assertGuideShows($this->guideLink('start', 'Terug naar start'));
    }

    public function test_a_namespaced_link_resolves_to_that_guide(): void
    {
        $this->assertGuideShows($this->guideLink('chief::paginas/status', 'Status'));
    }

    public function test_anchors_are_kept_on_resolved_links(): void
    {
        $this->assertGuideShows($this->guideLink('opleidingen/inschrijvingen', 'Inschrijven met anker', '#annuleren'));
        $this->assertGuideShows($this->guideLink('chief::paginas/status', 'Status met anker', '#publiceren'));
    }

    public function test_plain_urls_are_untouched(): void
    {
        $this->assertGuideShows('href="https://example.com/handleiding.md"');
        $this->assertGuideShows('<a href="mailto:info@example.com">Mail</a>');
        $this->assertGuideShows('<a href="#lokaal">Lokaal anker</a>');
        $this->assertGuideShows('href="https://example.com/Klasse::methode"');
    }

    public function test_a_relative_link_in_an_override_resolves_against_the_original_guide(): void
    {
        $this->asAdmin()->get('/admin/docs/chief::paginas')
            ->assertOk()
            ->assertSee($this->guideLink('chief::paginas/status', 'Status vanuit de override'), false);
    }

    public function test_links_to_missing_guides_render_as_plain_text(): void
    {
        $this->assertGuideShows('<p>Ontbrekende gids</p>');
        $this->assertGuideShows('<p>Ontbrekend ID</p>');
        $this->assertGuideShows('<p>Buiten de bron</p>');
    }

    public function test_links_to_hidden_guides_render_as_plain_text(): void
    {
        config()->set('chief-docs.hidden', ['opleidingen/verborgen']);

        $this->assertGuideShows('<p>Verborgen gids</p>');
    }

    public function test_links_to_guides_the_admin_may_not_see_render_as_plain_text(): void
    {
        $this->asAuthor()->get('/admin/docs/opleidingen')
            ->assertOk()
            ->assertSee('<p>Gids voor admins</p>', false);
    }

    public function test_links_to_restricted_guides_resolve_for_admins_who_may_see_them(): void
    {
        $this->assertGuideShows($this->guideLink('opleidingen/admins', 'Gids voor admins'));
    }

    private function assertGuideShows(string $html): void
    {
        $this->asAdmin()->get('/admin/docs/opleidingen')->assertOk()->assertSee($html, false);
    }

    private function guideLink(string $guideId, string $text, string $fragment = ''): string
    {
        return '<a href="'.route('chief.docs.show', $guideId).$fragment.'">'.$text.'</a>';
    }
}
