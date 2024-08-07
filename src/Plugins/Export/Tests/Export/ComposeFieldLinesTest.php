<?php

namespace Thinktomorrow\Chief\Plugins\Export\Tests\Export;

use Thinktomorrow\Chief\Fragments\Actions\PutFragmentOffline;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Plugins\Export\Export\Lines\ComposeFieldLines;
use Thinktomorrow\Chief\Plugins\Export\Tests\TestCase;

class ComposeFieldLinesTest extends TestCase
{
    public function test_it_can_export_localized_model_fields()
    {
        $article = $this->setUpAndCreateArticle(['title_trans' => ['nl' => 'title article nl', 'en' => 'title article en'], 'content_trans' => ['nl' => 'content article nl']]);
        $resource = app(Registry::class)->resource('article_page');

        $composeLines = app(ComposeFieldLines::class)
            ->ignoreEmptyValues()
            ->compose($resource, $article, ['nl', 'en']);

        $this->assertCount(2, $composeLines->getLines());
        $this->assertEquals('title article nl', $composeLines->getLines()->first()->getValue('nl'));
        $this->assertEquals('title article en', $composeLines->getLines()->first()->getValue('en'));
        $this->assertEquals('content article nl', $composeLines->getLines()[1]->getValue('nl'));
        $this->assertEquals(null, $composeLines->getLines()[1]->getValue('en'));
    }

    public function test_it_can_export_specific_locales()
    {
        $article = $this->setUpAndCreateArticle(['title_trans' => ['nl' => 'title article nl', 'en' => 'title article en'], 'content_trans' => ['nl' => 'content article nl', 'en' => 'content article en']]);
        $resource = app(Registry::class)->resource('article_page');

        $composeLines = app(ComposeFieldLines::class)
            ->ignoreEmptyValues()
            ->compose($resource, $article, ['en']);

        $this->assertEquals('title article en', $composeLines->getLines()->first()->getValue('en'));
        $this->assertEquals(null, $composeLines->getLines()->first()->getValue('nl'));
        $this->assertEquals('content article en', $composeLines->getLines()[1]->getValue('en'));
        $this->assertEquals(null, $composeLines->getLines()[1]->getValue('nl'));
    }

    public function test_it_can_export_non_localized_model_fields()
    {
        $article = $this->setUpAndCreateArticle(['title' => 'title article', 'custom' => 'content article']);
        $resource = app(Registry::class)->resource('article_page');

        $composeLines = app(ComposeFieldLines::class)
            ->ignoreEmptyValues()
            ->compose($resource, $article, ['nl', 'en']);

        $this->assertEquals('title article', $composeLines->getLines()->first()->getValue());
        $this->assertEquals(null, $composeLines->getLines()->first()->getValue('nl'));
        $this->assertEquals(null, $composeLines->getLines()->first()->getValue('en'));
        $this->assertEquals('content article', $composeLines->getLines()[1]->getValue());
        $this->assertEquals(null, $composeLines->getLines()[1]->getValue('nl'));
        $this->assertEquals(null, $composeLines->getLines()[1]->getValue('en'));
    }

    public function test_it_includes_empty_values_by_default()
    {
        $article = $this->setUpAndCreateArticle([]);
        $resource = app(Registry::class)->resource('article_page');

        $composeLines = app(ComposeFieldLines::class)
            ->compose($resource, $article, ['nl', 'en']);

        $this->assertTrue(count($composeLines->getLines()) > 3); // It's more than 3 but this just asserts there are lines
        $this->assertEquals(null, $composeLines->getLines()[1]->getValue('nl'));
        $this->assertEquals(null, $composeLines->getLines()[2]->getValue('nl'));
    }

    public function test_it_can_ignore_empty_values()
    {
        $article = $this->setUpAndCreateArticle([]);
        $resource = app(Registry::class)->resource('article_page');

        $composeLines = app(ComposeFieldLines::class)
            ->ignoreEmptyValues()
            ->compose($resource, $article, ['nl', 'en']);

        $this->assertCount(0, $composeLines->getLines());
    }

    public function test_it_can_ignore_non_localized()
    {
        $article = $this->setUpAndCreateArticle([]);
        $resource = app(Registry::class)->resource('article_page');

        $composeLines = app(ComposeFieldLines::class)
            ->ignoreNonLocalized()
            ->compose($resource, $article, ['nl', 'en']);

        $this->assertCount(3, $composeLines->getLines());
        $this->assertStringEndsWith('_trans', decrypt($composeLines->getLines()->first()->getReference()));
        $this->assertStringEndsWith('_trans', decrypt($composeLines->getLines()[1]->getReference()));
        $this->assertStringEndsWith('_trans', decrypt($composeLines->getLines()[2]->getReference()));
    }

    public function test_it_exports_fragments()
    {
        $article = $this->setUpAndCreateArticle([]);
        $resource = app(Registry::class)->resource('article_page');
        $this->setUpAndCreateSnippet($article, 0, true, ['title' => 'quote title', 'title_trans' => ['nl' => 'title quote nl', 'en' => 'title quote en']]);

        $composeLines = app(ComposeFieldLines::class)
            ->ignoreEmptyValues()
            ->compose($resource, $article, ['nl', 'en']);

        $this->assertCount(2, $composeLines->getLines());
        $this->assertStringEndsWith('title', decrypt($composeLines->getLines()->first()->getReference()));
        $this->assertStringEndsWith('title_trans', decrypt($composeLines->getLines()[1]->getReference()));
    }

    public function test_it_can_ignore_offline_fragments()
    {
        $article = $this->setUpAndCreateArticle([]);
        $resource = app(Registry::class)->resource('article_page');
        $snippet = $this->setUpAndCreateSnippet($article, 0, true, ['title' => 'quote title', 'title_trans' => ['nl' => 'title quote nl', 'en' => 'title quote en']]);
        app(PutFragmentOffline::class)->handle($snippet->fragmentModel()->id);

        $this->assertTrue($snippet->fragmentModel()->fresh()->isOffline());

        $composeLines = app(ComposeFieldLines::class)
            ->ignoreEmptyValues()
            ->ignoreOfflineFragments()
            ->compose($resource, $article, ['nl', 'en']);

        $this->assertCount(0, $composeLines->getLines());
    }
}
