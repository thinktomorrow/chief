<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Docs\Tests;

use Thinktomorrow\Chief\Plugins\AdminToast\AdminToastServiceProvider;
use Thinktomorrow\Chief\Plugins\Docs\DocSource;
use Thinktomorrow\Chief\Plugins\Docs\DocSources;
use Thinktomorrow\Chief\Plugins\Docs\GuideTree;
use Thinktomorrow\Chief\Plugins\Docs\GuideTreeBuilder;
use Thinktomorrow\Chief\Plugins\ExternalFiles\Cloudinary\CloudinaryServiceProvider;
use Thinktomorrow\Chief\Plugins\ExternalFiles\Vimeo\VimeoServiceProvider;
use Thinktomorrow\Chief\Plugins\ExternalFiles\Youtube\YoutubeServiceProvider;
use Thinktomorrow\Chief\Plugins\HotSpots\HotSpotsServiceProvider;
use Thinktomorrow\Chief\Plugins\Seo\SeoServiceProvider;
use Thinktomorrow\Chief\Plugins\Tags\TagsServiceProvider;
use Thinktomorrow\Chief\Plugins\TimeTable\TimeTableServiceProvider;

/**
 * Shipped help never contains broken links: the guides of Chief and its
 * plugins are checked as they are, without a project's guides.
 */
class ShippedGuidesTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            ...parent::getPackageProviders($app),
            AdminToastServiceProvider::class,
            TagsServiceProvider::class,
            TimeTableServiceProvider::class,
            HotSpotsServiceProvider::class,
            SeoServiceProvider::class,
            VimeoServiceProvider::class,
            YoutubeServiceProvider::class,
            CloudinaryServiceProvider::class,
        ];
    }

    public function test_the_shipped_guides_have_no_problems(): void
    {
        $this->useProjectGuides($this->fixture('geen-project'));

        $this->assertSame([], $this->problems());
    }

    public function test_the_guides_of_every_chief_plugin_are_checked(): void
    {
        $this->useProjectGuides($this->fixture('geen-project'));

        $checkedFolders = array_map(
            fn (DocSource $source) => realpath($source->path),
            array_filter(app(DocSources::class)->all(), fn (DocSource $source) => $source->namespace !== null),
        );

        $shippedFolders = array_map('realpath', glob(__DIR__.'/../../*/resources/docs', GLOB_ONLYDIR));

        $this->assertNotEmpty($shippedFolders);
        $this->assertEqualsCanonicalizing($shippedFolders, $checkedFolders);
    }

    public function test_a_valid_guide_has_no_problems(): void
    {
        $this->useOnlyProjectGuides($this->fixture('check'));

        $problems = $this->problems();

        foreach (['geldig', 'opleidingen', 'opleidingen/inschrijvingen'] as $guideId) {
            $this->assertSame([], preg_grep('/^'.preg_quote($guideId, '/').':/', $problems));
        }
    }

    public function test_a_guide_without_title_description_or_order_fails(): void
    {
        $this->useOnlyProjectGuides($this->fixture('check'));

        $problems = $this->problems();

        $this->assertContains('zonder-titel: missing title', $problems);
        $this->assertContains('zonder-beschrijving: missing description', $problems);
        $this->assertContains('zonder-volgorde: missing order', $problems);
    }

    public function test_a_guide_with_an_unknown_parent_fails(): void
    {
        $this->useOnlyProjectGuides($this->fixture('check'));

        $this->assertContains('onbekende-ouder: unknown parent ["bestaat-niet"]', $this->problems());
    }

    public function test_a_link_or_anchor_that_does_not_resolve_fails(): void
    {
        $this->useOnlyProjectGuides($this->fixture('check'));

        $problems = $this->problems();

        $this->assertContains('gebroken: broken link [bestaat-niet.md]', $problems);
        $this->assertContains('gebroken: broken link [acme::bestaat-niet]', $problems);
        $this->assertContains('gebroken: unknown anchor [opleidingen/index.md#bestaat-niet]', $problems);
        $this->assertContains('gebroken: unknown anchor [#bestaat-niet]', $problems);
    }

    public function test_a_missing_relative_image_fails(): void
    {
        $this->useOnlyProjectGuides($this->fixture('check'));

        $this->assertContains('gebroken: missing image [ontbreekt.png]', $this->problems());
    }

    /**
     * @return list<string>
     */
    private function problems(): array
    {
        return app(GuideCheck::class)->problems($this->fullTree());
    }

    /**
     * Every guide regardless of roles or hiding, as the builder reads them.
     */
    private function fullTree(): GuideTree
    {
        return app(GuideTreeBuilder::class)->build(app(DocSources::class));
    }
}
