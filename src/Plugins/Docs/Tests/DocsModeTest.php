<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Docs\Tests;

class DocsModeTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->useProjectGuides($this->fixture('sidebar'));
    }

    public function test_a_guide_shows_the_guide_sidebar_instead_of_the_chief_nav(): void
    {
        $this->asAdmin()->get('/admin/docs/welkom')
            ->assertOk()
            ->assertSee('Terug naar Chief')
            ->assertSee('href="'.route('chief.back.dashboard').'"', false)
            ->assertSee('href="'.route('chief.docs.index').'"', false)
            ->assertSee('Handleiding bij Chief versie')
            ->assertDontSee('Je gebruikt momenteel Chief versie');
    }

    public function test_the_guide_sidebar_slides_in_on_narrow_screens_like_the_chief_nav(): void
    {
        $this->asAdmin()->get('/admin/docs/welkom')
            ->assertOk()
            ->assertSee('data-mobile-navigation-toggle', false)
            ->assertSee('data-mobile-navigation', false);
    }

    public function test_the_sidebar_lists_starting_guides_then_a_group_per_section_with_its_page_first(): void
    {
        $html = $this->asAdmin()->get('/admin/docs/welkom')->assertOk()->getContent();

        $this->assertMatchesRegularExpression($this->guideLinkPattern('opleidingen', 'Inleiding'), $html);
        $this->assertMatchesRegularExpression($this->guideLinkPattern('opleidingen/kalender', 'Kalender'), $html);

        $this->assertSeeInOrderIn($html, [
            'Aan de slag',
            $this->guideHref('welkom'),
            $this->guideHref('woordenlijst'),
            'Opleidingen',
            $this->guideHref('opleidingen'),
            $this->guideHref('opleidingen/kalender'),
            $this->guideHref('opleidingen/inschrijvingen'),
            'Beheer',
            'Handleiding bij Chief versie',
        ]);
    }

    public function test_a_section_without_page_is_not_a_link(): void
    {
        $this->asAdmin()->get('/admin/docs/opleidingen/beheer/rollen')
            ->assertOk()
            ->assertSee('Beheer')
            ->assertDontSee($this->guideHref('opleidingen/beheer'), false);
    }

    public function test_the_active_guide_is_highlighted_and_lists_its_h2_headings(): void
    {
        $html = $this->asAdmin()->get('/admin/docs/opleidingen/kalender')->assertOk()->getContent();

        $this->assertSame(1, substr_count($html, 'before:bg-primary-500'));
        $this->assertMatchesRegularExpression('#before:bg-primary-500.*?'.preg_quote($this->guideHref('opleidingen/kalender'), '#').'#s', $html);

        $this->assertStringContainsString('href="#een-sessie-toevoegen"', $html);
        $this->assertStringContainsString('href="#een-sessie-verplaatsen"', $html);
        $this->assertStringNotContainsString('href="#een-opleiding-aanmaken"', $html);
    }

    public function test_the_active_guide_opens_to_list_its_child_guides(): void
    {
        $html = $this->asAdmin()->get('/admin/docs/opleidingen/inschrijvingen')->assertOk()->getContent();

        $this->assertNavItemOpen($html, 'Inschrijvingen', true);
        $this->assertStringContainsString($this->guideHref('opleidingen/annuleren'), $html);

        $html = $this->asAdmin()->get('/admin/docs/welkom')->assertOk()->getContent();

        $this->assertNavItemOpen($html, 'Inschrijvingen', false);
    }

    public function test_the_ancestors_of_the_active_guide_are_open(): void
    {
        $html = $this->asAdmin()->get('/admin/docs/opleidingen/beheer/rollen')->assertOk()->getContent();

        $this->assertNavItemOpen($html, 'Beheer', true);

        $html = $this->asAdmin()->get('/admin/docs/welkom')->assertOk()->getContent();

        $this->assertNavItemOpen($html, 'Beheer', false);
    }

    public function test_the_section_page_lists_its_headings_as_inleiding(): void
    {
        $this->asAdmin()->get('/admin/docs/opleidingen')
            ->assertOk()
            ->assertSee('href="#een-opleiding-aanmaken"', false)
            ->assertDontSee('href="#een-sessie-toevoegen"', false);
    }

    public function test_the_sidebar_only_shows_guides_the_admin_may_see(): void
    {
        config()->set('chief-docs.hidden', ['woordenlijst']);

        $this->asAdmin()->get('/admin/docs/welkom')
            ->assertOk()
            ->assertSee($this->guideHref('instellingen/talen'), false)
            ->assertDontSee($this->guideHref('woordenlijst'), false);

        $this->asAdminWithoutRole()->get('/admin/docs/welkom')
            ->assertOk()
            ->assertDontSee($this->guideHref('instellingen/talen'), false);
    }

    public function test_the_article_shows_the_section_title_the_guide_title_and_its_description(): void
    {
        $html = $this->asAdmin()->get('/admin/docs/opleidingen/kalender')->assertOk()->getContent();
        $article = substr($html, strpos($html, '<article'));

        $this->assertSeeInOrderIn($article, [
            'Opleidingen',
            'Kalender</h1>',
            'Plan de sessies van een opleiding.',
            'id="een-sessie-toevoegen"',
        ]);
        $this->assertSame(1, substr_count($html, '<h1'));
        $this->assertStringNotContainsString('Documentatie', $html);
    }

    public function test_a_top_level_guide_has_no_section_title(): void
    {
        $html = $this->asAdmin()->get('/admin/docs/welkom')->assertOk()->getContent();
        $article = substr($html, strpos($html, '<article'));

        $this->assertMatchesRegularExpression('#^<article[^>]*>\s*<div[^>]*>\s*<h1#', $article);
    }

    public function test_the_overview_uses_the_guide_sidebar_with_handleiding_active(): void
    {
        $html = $this->asAdmin()->get('/admin/docs')->assertOk()->getContent();

        $this->assertStringNotContainsString('Je gebruikt momenteel Chief versie', $html);
        $this->assertMatchesRegularExpression('#before:bg-primary-500.*?href="'.preg_quote(route('chief.docs.index'), '#').'"#s', $html);
    }

    private function guideHref(string $id): string
    {
        return 'href="'.route('chief.docs.show', $id).'"';
    }

    private function guideLinkPattern(string $id, string $label): string
    {
        return '#'.preg_quote($this->guideHref($id), '#').'\s+title="'.preg_quote($label, '#').'"#';
    }

    /**
     * @param  list<string>  $values
     */
    private function assertSeeInOrderIn(string $html, array $values): void
    {
        $position = 0;

        foreach ($values as $value) {
            $found = strpos($html, $value, $position);

            $this->assertNotFalse($found, 'Failed asserting that ['.$value.'] appears in order.');

            $position = $found + strlen($value);
        }
    }

    private function assertNavItemOpen(string $html, string $label, bool $isOpen): void
    {
        $this->assertMatchesRegularExpression(
            '#x-data="\{ isOpen: '.($isOpen ? 'true' : 'false').' \}"(?:(?!x-data=).)*?>\s*'.preg_quote($label, '#').'\s*<#s',
            $html,
        );
    }
}
