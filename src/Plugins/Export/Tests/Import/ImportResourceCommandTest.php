<?php

namespace Thinktomorrow\Chief\Plugins\Export\Tests\Import;

use Illuminate\Support\Facades\Storage;
use Thinktomorrow\Chief\Fragments\Tests\FragmentTestHelpers;
use Thinktomorrow\Chief\Plugins\Export\Tests\TestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes\SnippetStub;

class ImportResourceCommandTest extends TestCase
{
    public function test_it_can_import_resource_for_locale()
    {
        $article = $this->setUpAndCreateArticle(['title' => ['nl' => 'article title nl', 'en' => 'article title en'], 'content_trans' => ['nl' => 'content article nl', 'en' => 'content article en']]);
        [, $fragment] = FragmentTestHelpers::createContextAndAttachFragment($article, SnippetStub::class, null, 0, ['title' => 'quote title', 'title_trans' => ['nl' => 'title quote nl', 'en' => 'title quote en']]);

        $this->artisan('chief:export-resource article_page');

        $filepath = Storage::disk('local')->path('exports/'.date('Ymd').'/'.config('app.name').'-article_page-'.date('Y-m-d').'.xlsx');

        // Change the database static text
        $article->update(['custom' => ['nl' => 'changed custom nl', 'en' => 'changed custom en']]);
        $fragment->getFragmentModel()->title = 'changed quote title';
        $fragment->getFragmentModel()->setDynamic('title_trans.nl', 'changed quote title nl');
        $fragment->getFragmentModel()->setDynamic('title_trans.en', 'changed quote title en');
        $fragment->getFragmentModel()->save();

        // Now import it again
        $this->artisan('chief:import-resource', ['file' => $filepath])
            ->expectsQuestion('Which column contains the ID references? Choose one of: ID, Pagina, Fragment, Element, nl, en, Opmerking', 'ID')
            ->expectsQuestion('Which column would you like to import? Choose one of: ID, Pagina, Fragment, Element, nl, en, Opmerking', 'nl')
            ->expectsQuestion('Which locale does this column represent? Choose one of: nl, en', 'nl');

        // Localized values
        $this->assertEquals('title quote nl', $fragment->getFragmentModel()->fresh()->dynamic('title_trans', 'nl'));
        $this->assertEquals('changed quote title en', $fragment->getFragmentModel()->fresh()->dynamic('title_trans', 'en'));

        // Non-localized values are unchanged
        $this->assertEquals('changed custom nl', $article->fresh()->custom);
        $this->assertEquals('changed quote title', $fragment->getFragmentModel()->fresh()->title);
    }

    public function test_it_can_import_resource_for_non_localized_values()
    {
        $article = $this->setUpAndCreateArticle(['custom' => 'custom title', 'title' => ['nl' => 'article title nl', 'en' => 'article title en'], 'content_trans' => ['nl' => 'content article nl', 'en' => 'content article en']]);
        [, $fragment] = FragmentTestHelpers::createContextAndAttachFragment($article, SnippetStub::class, null, 0, ['title' => 'quote title', 'title_trans' => ['nl' => 'title quote nl', 'en' => 'title quote en']]);

        $this->artisan('chief:export-resource article_page --include-static');

        $filepath = Storage::disk('local')->path('exports/'.date('Ymd').'/'.config('app.name').'-article_page-'.date('Y-m-d').'.xlsx');

        // Change the database text
        $article->update(['custom' => 'changed custom']);
        $article->update(['title' => ['nl' => 'changed title nl', 'en' => 'changed title en']]);
        $fragment->getFragmentModel()->title = 'changed quote title';
        $fragment->getFragmentModel()->setDynamic('title_trans.nl', 'changed quote title nl');
        $fragment->getFragmentModel()->setDynamic('title_trans.en', 'changed quote title en');
        $fragment->getFragmentModel()->save();

        // Now import it again
        $this->artisan('chief:import-resource', ['file' => $filepath])
            ->expectsQuestion('Which column contains the ID references? Choose one of: ID, Pagina, Fragment, Element, Tekst, nl, en, Opmerking', 'ID')
            ->expectsQuestion('Which column would you like to import? Choose one of: ID, Pagina, Fragment, Element, Tekst, nl, en, Opmerking', 'Tekst');

        // Non-localized values
        $this->assertEquals('custom title', $article->fresh()->custom);
        $this->assertEquals('quote title', $fragment->getFragmentModel()->fresh()->title);

        // Localized values are unchanged
        $this->assertEquals('changed title nl', $article->fresh()->title);
        $this->assertEquals('changed quote title nl', $fragment->getFragmentModel()->fresh()->dynamic('title_trans', 'nl'));
        $this->assertEquals('changed quote title en', $fragment->getFragmentModel()->fresh()->dynamic('title_trans', 'en'));

    }
}
