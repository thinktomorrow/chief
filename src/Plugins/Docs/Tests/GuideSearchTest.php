<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Docs\Tests;

use Livewire\Features\SupportTesting\Testable;
use Livewire\Livewire;
use Thinktomorrow\Chief\Plugins\Docs\App\Livewire\GuideSearchComponent;
use Thinktomorrow\Chief\Plugins\Docs\Search\GuideSearchResult;

class GuideSearchTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->useOnlyProjectGuides($this->fixture('search'));
    }

    public function test_it_matches_the_title_description_headings_and_body(): void
    {
        $this->assertSame(['paginas/status#archiveren'], $this->resultUrls('archiveren'));
        $this->assertSame(['welkom'], $this->resultUrls('stappen'));
        $this->assertSame(['welkom'], $this->resultUrls('verkennen'));
        $this->assertSame(['welkom'], $this->resultUrls('welkom'));
    }

    public function test_every_word_of_the_query_must_appear_in_the_guide(): void
    {
        $this->assertSame(['welkom'], $this->resultUrls('redactie website'));
        $this->assertSame([], $this->resultUrls('redactie archief'));
    }

    public function test_words_match_as_case_and_diacritic_insensitive_substrings(): void
    {
        $this->assertSame(['welkom'], $this->resultUrls('CAFE'));
        $this->assertSame(['welkom'], $this->resultUrls('cafÉ'));
        $this->assertSame(['paginas#publiceren'], $this->resultUrls('publ'));
        $this->assertContains('paginas', $this->resultUrls('pagina\'s'));
    }

    public function test_a_query_under_two_characters_returns_nothing_and_shows_a_hint(): void
    {
        $this->search(' p ')
            ->assertViewHas('results', [])
            ->assertSee('Typ minstens 2 tekens');
    }

    public function test_without_matches_it_says_nothing_was_found(): void
    {
        $this->search('fietsenstalling')
            ->assertViewHas('results', [])
            ->assertSee('Niets gevonden voor “fietsenstalling”');
    }

    public function test_title_matches_rank_before_other_matches_and_ties_follow_the_sidebar_order(): void
    {
        $this->assertSame(['paginas/status#status-wijzigen', 'paginas'], $this->resultUrls('status'));
        $this->assertSame(['paginas', 'paginas/status#status-wijzigen'], $this->resultUrls('pagina status'));
    }

    public function test_a_result_shows_its_section_and_title(): void
    {
        $result = $this->search('archiveren')->viewData('results')[0];

        $this->assertSame('Pagina\'s', $result->section);
        $this->assertSame('Status', $result->title);
        $this->assertNull($this->search('verkennen')->viewData('results')[0]->section);
    }

    public function test_the_snippet_marks_the_matched_words_around_the_first_body_match(): void
    {
        $this->search('cafe redactie')
            ->assertSeeHtml('Spreek dan iemand aan in het <mark>café</mark> van de <mark>redactie</mark>.')
            ->assertDontSeeHtml('Chief beheert');
    }

    public function test_the_snippet_leaves_out_raw_html(): void
    {
        $this->search('archief')->assertSeeHtml('zet je in het <mark>archief</mark>.');
    }

    public function test_the_snippet_is_the_description_when_only_the_title_or_description_matched(): void
    {
        $this->search('stappen')->assertSeeHtml('Je eerste <mark>stappen</mark> in het beheer.');
        $this->search('welkom')->assertSeeHtml('Je eerste stappen in het beheer.');
    }

    public function test_a_long_snippet_is_cut_to_about_160_characters_at_word_boundaries(): void
    {
        $this->useOnlyProjectGuides($this->fixture('search-many'));

        $snippet = (string) $this->search('zoekterm')->viewData('results')[0]->snippet;

        $this->assertSame('… Een vijfde zin over het beheer. De <mark>zoekterm</mark> staat hier. Een zesde zin over het beheer. Een zevende zin over het beheer. Een achtste zin over het beheer. …', $snippet);
    }

    public function test_a_guide_hidden_or_restricted_to_other_roles_is_never_returned(): void
    {
        config()->set('chief-docs.hidden', ['verborgen']);

        $this->assertSame(['geheim'], $this->resultUrls('wachtwoord'));

        $this->asAdminWithoutRole();
        $this->app->forgetScopedInstances();

        $this->assertSame([], array_map(
            fn (GuideSearchResult $result) => $result->title,
            Livewire::test(GuideSearchComponent::class)->set('query', 'wachtwoord')->viewData('results'),
        ));
    }

    public function test_it_shows_at_most_eight_results(): void
    {
        $this->useOnlyProjectGuides($this->fixture('search-many'));

        $this->assertCount(8, $this->resultUrls('handleiding'));
    }

    public function test_the_overview_and_every_guide_have_the_search_bar_and_dialog(): void
    {
        foreach (['/admin/docs', '/admin/docs/paginas/status'] as $url) {
            $html = $this->asAdmin()->get($url)->assertOk()->getContent();

            $this->assertMatchesRegularExpression('#<button[^>]*x-on:click="\$dispatch\(\'open-dialog\', \{ id: \'guide-search\' \}\)"[^>]*>.*?Zoek in de handleiding….*?⌘K.*?</button>#s', $html);
            $this->assertStringContainsString('id="guide-search"', $html);
            $this->assertStringContainsString('x-on:keydown.window.meta.k.prevent', $html);
            $this->assertStringContainsString('x-on:keydown.window.ctrl.k.prevent', $html);
        }
    }

    public function test_the_overview_has_a_search_button_next_to_getting_started(): void
    {
        $html = $this->asAdmin()->get('/admin/docs')->assertOk()->getContent();

        $this->assertMatchesRegularExpression('#Aan de slag\s*</a>\s*<button[^>]*x-on:click="\$dispatch\(\'open-dialog\', \{ id: \'guide-search\' \}\)"[^>]*>.*?Zoeken\s*</button>#s', $html);
    }

    private function search(string $query): Testable
    {
        $this->asAdmin();
        $this->app->forgetScopedInstances();

        return Livewire::test(GuideSearchComponent::class)->set('query', $query);
    }

    /**
     * @return list<string> The result URLs relative to the docs, with their anchors.
     */
    private function resultUrls(string $query): array
    {
        return array_map(
            fn (GuideSearchResult $result) => str_replace(route('chief.docs.index').'/', '', $result->url),
            $this->search($query)->viewData('results'),
        );
    }
}
