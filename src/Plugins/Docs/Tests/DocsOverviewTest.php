<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Docs\Tests;

class DocsOverviewTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->useOnlyProjectGuides($this->fixture('sidebar'));
    }

    public function test_the_overview_opens_with_the_landing_header_and_a_button_to_the_first_guide(): void
    {
        $article = $this->article($this->asAdmin()->get('/admin/docs')->assertOk()->getContent());

        $this->assertSeeInOrderIn($article, ['Handleiding', 'Hoe werkt Chief?</h1>']);
        $this->assertMatchesRegularExpression('#<a[^>]*href="'.preg_quote(route('chief.docs.show', 'welkom'), '#').'"[^>]*>\s*Aan de slag\s*</a>#', $article);
    }

    public function test_the_overview_lists_a_card_grid_per_sidebar_group(): void
    {
        $article = $this->article($this->asAdmin()->get('/admin/docs')->assertOk()->getContent());

        $this->assertSeeInOrderIn($article, [
            'Aan de slag</h2>',
            $this->guideHref('welkom'),
            'Zo vind je de weg.',
            'Lees meer',
            $this->guideHref('woordenlijst'),
            'Opleidingen</h2>',
            $this->guideHref('opleidingen'),
            'Een overzicht van alle opleidingen.',
            $this->guideHref('opleidingen/kalender'),
            $this->guideHref('opleidingen/inschrijvingen'),
            $this->guideHref('opleidingen/beheer/rollen'),
            'Instellingen</h2>',
            $this->guideHref('instellingen/talen'),
        ]);
    }

    public function test_the_overview_replaces_sections_without_page_by_their_guides_the_admin_may_see(): void
    {
        $article = $this->article($this->asAdminWithoutRole()->get('/admin/docs')->assertOk()->getContent());

        $this->assertStringContainsString($this->guideHref('opleidingen/beheer/rollen'), $article);
        $this->assertStringNotContainsString('Beheer', $article);
        $this->assertStringNotContainsString('Instellingen', $article);
        $this->assertStringNotContainsString($this->guideHref('instellingen/talen'), $article);
    }

    public function test_without_visible_guides_the_overview_shows_the_empty_state(): void
    {
        config()->set('chief-docs.hidden', ['welkom', 'woordenlijst', 'opleidingen', 'instellingen']);

        $html = $this->asAdmin()->get('/admin/docs')->assertOk()->getContent();
        $article = $this->article($html);

        $this->assertStringContainsString('Hoe werkt Chief?', $article);
        $this->assertStringContainsString('Er is nog geen handleiding', $article);
        $this->assertStringNotContainsString('Aan de slag', $html);
        $this->assertStringNotContainsString('Lees meer', $html);
    }

    public function test_without_a_guide_folder_the_overview_shows_the_empty_state_and_creates_nothing(): void
    {
        $path = $this->fixture('ontbrekend');
        $this->useOnlyProjectGuides($path);

        $this->asAdmin()->get('/admin/docs')
            ->assertOk()
            ->assertSee('Er is nog geen handleiding');

        $this->assertFileDoesNotExist($path);
    }

    private function article(string $html): string
    {
        return substr($html, strpos($html, '<article'));
    }

    private function guideHref(string $id): string
    {
        return 'href="'.route('chief.docs.show', $id).'"';
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
}
