<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Docs\Tests;

class GuideEndingsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->useOnlyProjectGuides($this->fixture('sidebar'));
    }

    public function test_a_guide_ends_with_links_to_the_previous_and_next_guide(): void
    {
        $html = $this->asAdmin()->get('/admin/docs/opleidingen/beheer/rollen')->assertOk()->getContent();

        $this->assertMatchesRegularExpression($this->pagerLinkPattern('opleidingen/annuleren', 'Vorige', 'Annuleren'), $html);
        $this->assertMatchesRegularExpression($this->pagerLinkPattern('instellingen/talen', 'Volgende', 'Talen'), $html);
    }

    public function test_the_first_guide_has_no_previous_link(): void
    {
        $html = $this->asAdmin()->get('/admin/docs/welkom')->assertOk()->getContent();

        $this->assertStringNotContainsString('Vorige', $html);
        $this->assertMatchesRegularExpression($this->pagerLinkPattern('woordenlijst', 'Volgende', 'Woordenlijst'), $html);
    }

    public function test_previous_and_next_skip_guides_the_admin_may_not_see(): void
    {
        config()->set('chief-docs.hidden', ['opleidingen/annuleren']);

        $html = $this->asAdminWithoutRole()->get('/admin/docs/opleidingen/beheer/rollen')->assertOk()->getContent();

        $this->assertMatchesRegularExpression($this->pagerLinkPattern('opleidingen/inschrijvingen', 'Vorige', 'Inschrijvingen'), $html);
        $this->assertStringNotContainsString('Volgende', $html);
    }

    public function test_a_guide_with_children_ends_with_a_card_per_child_guide(): void
    {
        $html = $this->asAdmin()->get('/admin/docs/opleidingen')->assertOk()->getContent();
        $ending = substr($html, strpos($html, 'In dit onderdeel'));

        $this->assertMatchesRegularExpression($this->cardPattern('opleidingen/kalender', 'Kalender', 'Plan de sessies van een opleiding.'), $ending);
        $this->assertMatchesRegularExpression($this->cardPattern('opleidingen/inschrijvingen', 'Inschrijvingen'), $ending);
        $this->assertStringContainsString('Lees meer', $ending);
    }

    public function test_a_child_section_without_page_is_replaced_by_cards_for_its_guides(): void
    {
        $html = $this->asAdmin()->get('/admin/docs/opleidingen')->assertOk()->getContent();
        $ending = substr($html, strpos($html, 'In dit onderdeel'));

        $this->assertMatchesRegularExpression($this->cardPattern('opleidingen/beheer/rollen', 'Rollen'), $ending);
        $this->assertStringNotContainsString('Beheer', $ending);
    }

    public function test_child_cards_leave_out_guides_the_admin_may_not_see(): void
    {
        config()->set('chief-docs.hidden', ['opleidingen/inschrijvingen']);

        $html = $this->asAdmin()->get('/admin/docs/opleidingen')->assertOk()->getContent();
        $ending = substr($html, strpos($html, 'In dit onderdeel'));

        $this->assertMatchesRegularExpression($this->cardPattern('opleidingen/kalender', 'Kalender'), $ending);
        $this->assertStringNotContainsString(route('chief.docs.show', 'opleidingen/inschrijvingen'), $ending);
    }

    public function test_a_guide_without_children_has_no_child_cards(): void
    {
        $this->asAdmin()->get('/admin/docs/opleidingen/kalender')
            ->assertOk()
            ->assertDontSee('In dit onderdeel');
    }

    private function cardPattern(string $id, string $title, string $description = ''): string
    {
        $href = preg_quote('href="'.route('chief.docs.show', $id).'"', '#');

        return '#<a[^>]*'.$href.'[^>]*>(?:(?!</a>).)*'.preg_quote($title, '#').'(?:(?!</a>).)*'.preg_quote($description, '#').'(?:(?!</a>).)*Lees meer(?:(?!</a>).)*</a>#s';
    }

    private function pagerLinkPattern(string $id, string $direction, string $title): string
    {
        $href = preg_quote('href="'.route('chief.docs.show', $id).'"', '#');
        $direction = preg_quote($direction, '#');
        $title = preg_quote($title, '#');

        return '#<a[^>]*'.$href.'[^>]*>(?:(?!</a>).)*(?:'.$direction.'(?:(?!</a>).)*'.$title.'|'.$title.'(?:(?!</a>).)*'.$direction.')(?:(?!</a>).)*</a>#s';
    }
}
