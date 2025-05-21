<?php

namespace Export\Tests\Import;

use Illuminate\Support\Facades\Storage;
use Thinktomorrow\Chief\Fragments\Tests\FragmentTestHelpers;
use Thinktomorrow\Chief\Plugins\Export\Tests\TestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes\SnippetStub;

class ImportRepeatLinesTest extends TestCase
{
    public function test_it_can_import_localized_repeat_lines()
    {
        $this->disableExceptionHandling();
        $article = $this->setUpAndCreateArticle(['title' => ['nl' => 'article title nl', 'en' => 'article title en'], 'content_trans' => ['nl' => 'content article nl', 'en' => 'content article en']]);
        [, $fragment] = FragmentTestHelpers::createContextAndAttachFragment($article, SnippetStub::class, null, 0, [
            'title' => 'quote title',
            'title_trans' => ['nl' => 'title quote nl', 'en' => 'title quote en'],
            'links' => [
                'nl' => [
                    ['title' => 'link title nl', 'url' => 'https://example.com/nl'],
                ],
                'en' => [
                    ['title' => 'link title en', 'url' => 'https://example.com/en'],
                ],
            ],
        ]);

        $this->artisan('chief:export-resource article_page');

        $filepath = Storage::disk('local')->path('exports/'.date('Ymd').'/'.config('app.name').'-article_page-'.date('Y-m-d').'.xlsx');

        // Change the database static text
        $article->update(['custom' => ['nl' => 'changed custom nl', 'en' => 'changed custom en']]);
        $fragment->getFragmentModel()->title = 'changed quote title';
        $fragment->getFragmentModel()->setDynamic('links.nl.0.title', 'changed link title nl');
        $fragment->getFragmentModel()->setDynamic('links.nl.0.url', 'https://example.com/nl/changed');
        $fragment->getFragmentModel()->setDynamic('links.en.0.title', 'changed link title en');
        $fragment->getFragmentModel()->setDynamic('links.en.0.url', 'https://example.com/en/changed');
        $fragment->getFragmentModel()->save();

        // Validate proper repeat setup
        $this->assertEquals('changed link title nl', $fragment->getFragmentModel()->fresh()->dynamic('links.nl.0.title'));
        $this->assertEquals('https://example.com/nl/changed', $fragment->getFragmentModel()->fresh()->dynamic('links.nl.0.url'));
        $this->assertEquals('changed link title en', $fragment->getFragmentModel()->fresh()->dynamic('links.en.0.title'));
        $this->assertEquals('https://example.com/en/changed', $fragment->getFragmentModel()->fresh()->dynamic('links.en.0.url'));

        // Now import it again
        $this->artisan('chief:import-resource', ['file' => $filepath])
            ->expectsQuestion('Which column contains the ID references? Choose one of: ID, Pagina, Fragment, Element, nl, en, Opmerking', 'ID')
            ->expectsQuestion('Which column would you like to import? Choose one of: ID, Pagina, Fragment, Element, nl, en, Opmerking', 'en')
            ->expectsQuestion('Which locale does this column represent? Choose one of: nl, en', 'en');

        // Nl is left unchanged
        $this->assertEquals('changed link title nl', $fragment->getFragmentModel()->fresh()->dynamic('links.nl.0.title'));
        $this->assertEquals('https://example.com/nl/changed', $fragment->getFragmentModel()->fresh()->dynamic('links.nl.0.url'));

        // En is changed
        $this->assertEquals('link title en', $fragment->getFragmentModel()->fresh()->dynamic('links.en.0.title'));
        $this->assertEquals('https://example.com/en', $fragment->getFragmentModel()->fresh()->dynamic('links.en.0.url'));
    }

    public function test_it_can_import_resource_for_non_localized_repeat_lines() {}
}
