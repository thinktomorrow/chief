<?php

namespace Thinktomorrow\Chief\Plugins\Export\Tests\Import;

use Thinktomorrow\Chief\Plugins\Export\Tests\TestCase;

class ImportResourceCommandTest extends TestCase
{
    public function test_it_can_import_resource_for_locale()
    {
        $article = $this->setUpAndCreateArticle(['title' => 'article title', 'content_trans' => ['nl' => 'content article nl', 'en' => 'content article en']]);
        $snippet = $this->setUpAndCreateSnippet($article, 0, true, ['title' => 'quote title', 'title_trans' => ['nl' => 'title quote nl', 'en' => 'title quote en']]);

        $this->artisan('chief:export-resource article_page');

        $filepath = storage_path('app/'.config('app.name') .'-article_page-'.date('Y-m-d').'.xlsx');

        // Change the database text
        $article->update(['title' => 'changed title']);
        $snippet->fragmentModel()->title = 'changed quote title';
        $snippet->fragmentModel()->setDynamic('title_trans.nl', 'changed quote title nl');
        $snippet->fragmentModel()->setDynamic('title_trans.en', 'changed quote title en');
        $snippet->fragmentModel()->save();

        // Now import it again
        $this->artisan('chief:import-resource', ['file' => $filepath])
            ->expectsQuestion("Which column contains the ID references? Choose one of: id, pagina, fragment, element, nl, en, opmerking", 'id')
            ->expectsQuestion("Which column would you like to import? Choose one of: id, pagina, fragment, element, nl, en, opmerking", 'nl')
            ->expectsQuestion("Which locale does this column represent? Choose one of: nl, en", 'nl');

        // Localized values
        $this->assertEquals('title quote nl', $snippet->fragmentModel()->fresh()->dynamic('title_trans', 'nl'));
        $this->assertEquals('changed quote title en', $snippet->fragmentModel()->fresh()->dynamic('title_trans', 'en'));

        // Non-localized values are unchanged
        $this->assertEquals('changed title', $article->fresh()->title);
        $this->assertEquals('changed quote title', $snippet->fragmentModel()->fresh()->title);
    }

    public function test_it_can_import_resource_for_non_localized_values()
    {
        $article = $this->setUpAndCreateArticle(['title' => 'article title', 'content_trans' => ['nl' => 'content article nl', 'en' => 'content article en']]);
        $snippet = $this->setUpAndCreateSnippet($article, 0, true, ['title' => 'quote title', 'title_trans' => ['nl' => 'title quote nl', 'en' => 'title quote en']]);

        $this->artisan('chief:export-resource article_page --include-static');

        $filepath = storage_path('app/'.config('app.name') .'-article_page-'.date('Y-m-d').'.xlsx');

        // Change the database text
        $article->update(['title' => 'changed title']);
        $snippet->fragmentModel()->title = 'changed quote title';
        $snippet->fragmentModel()->setDynamic('title_trans.nl', 'changed quote title nl');
        $snippet->fragmentModel()->setDynamic('title_trans.en', 'changed quote title en');
        $snippet->fragmentModel()->save();

        // Now import it again
        $this->artisan('chief:import-resource', ['file' => $filepath])
            ->expectsQuestion("Which column contains the ID references? Choose one of: id, pagina, fragment, element, tekst, nl, en, opmerking", 'id')
            ->expectsQuestion("Which column would you like to import? Choose one of: id, pagina, fragment, element, tekst, nl, en, opmerking", 'tekst');

        // Non-localized values
        $this->assertEquals('article title', $article->fresh()->title);
        $this->assertEquals('quote title', $snippet->fragmentModel()->fresh()->title);

        // Localized values are unchanged
        $this->assertEquals('changed quote title nl', $snippet->fragmentModel()->fresh()->dynamic('title_trans', 'nl'));
        $this->assertEquals('changed quote title en', $snippet->fragmentModel()->fresh()->dynamic('title_trans', 'en'));


    }
}
