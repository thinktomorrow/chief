<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Docs\Tests;

use Thinktomorrow\Chief\Plugins\AdminToast\AdminToastServiceProvider;
use Thinktomorrow\Chief\Plugins\ExternalFiles\Cloudinary\CloudinaryServiceProvider;
use Thinktomorrow\Chief\Plugins\ExternalFiles\Vimeo\VimeoServiceProvider;
use Thinktomorrow\Chief\Plugins\ExternalFiles\Youtube\YoutubeServiceProvider;
use Thinktomorrow\Chief\Plugins\HotSpots\HotSpotsServiceProvider;
use Thinktomorrow\Chief\Plugins\Seo\SeoServiceProvider;
use Thinktomorrow\Chief\Plugins\Tags\TagsServiceProvider;
use Thinktomorrow\Chief\Plugins\TimeTable\TimeTableServiceProvider;

class PluginGuidesTest extends TestCase
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

    protected function setUp(): void
    {
        parent::setUp();

        $this->useProjectGuides($this->fixture('project'));
    }

    public function test_a_plugin_guide_with_a_parent_appears_under_the_chief_section(): void
    {
        $tree = $this->guideTree();

        $guide = $tree->find('hot-spots::hotspots');

        $this->assertSame('Hotspots op afbeeldingen', $guide->title);
        $this->assertSame(['chief::media'], array_map(fn ($node) => $node->id, $tree->ancestors($guide)));
    }

    public function test_shipped_plugin_guides_appear_under_their_namespaces(): void
    {
        $tree = $this->guideTree();

        $this->assertSame('Voorbeeld bekijken op de website', $tree->find('admin-toast::voorbeeld')->title);
        $this->assertSame('Tags', $tree->find('tags::tags')->title);
        $this->assertSame("Weekschema's en openingsuren", $tree->find('time-table::weekschema')->title);
        $this->assertSame('Alt-teksten beheren', $tree->find('seo::alt-teksten')->title);
    }

    public function test_several_external_files_drivers_share_one_guide_source_without_a_duplicate_error(): void
    {
        $tree = $this->guideTree();

        $this->assertSame('Externe bestanden', $tree->find('external-files::externe-bestanden')->title);
    }
}
