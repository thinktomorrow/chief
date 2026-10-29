<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Docs\Tests;

use Thinktomorrow\Chief\Plugins\Docs\GuideNode;

class OverrideGuidesTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->useProjectGuides($this->fixture('overrides'));
    }

    public function test_a_project_override_fully_replaces_a_chief_guide(): void
    {
        $guide = $this->guideTree()->find('chief::teksten');

        $this->assertSame('Vertalingen', $guide->title);
        $this->assertSame('Vertalingen van de website.', $guide->description);
        $this->assertNull($guide->order);
        $this->assertSame(['chief::paginas'], array_map(fn (GuideNode $node) => $node->id, $this->guideTree()->ancestors($guide)));

        $this->asAdmin()->get('/admin/docs/chief::teksten')
            ->assertOk()
            ->assertSee('Eigen uitleg over vertalingen.')
            ->assertDontSee('Vaste teksten van de website opzoeken');
    }

    public function test_a_section_page_is_overridden_at_the_section_id(): void
    {
        $this->assertSame("Eigen pagina's", $this->guideTree()->find('chief::paginas')->title);

        $this->asAdmin()->get('/admin/docs/chief::paginas')
            ->assertOk()
            ->assertSee('Eigen uitleg over pagina')
            ->assertDontSee('Een nieuwe pagina aanmaken');
    }

    public function test_an_override_without_an_original_is_ignored(): void
    {
        $this->assertNull($this->guideTree()->find('chief::onbestaand'));
        $this->assertNull($this->guideTree()->find('onbekend::gids'));

        $this->asAdmin()->get('/admin/docs/chief::onbestaand')->assertNotFound();
        $this->asAdmin()->get('/admin/docs')
            ->assertOk()
            ->assertDontSee('Weesoverschrijving')
            ->assertDontSee('Onbekende bron');
    }

    public function test_override_files_are_not_project_guides(): void
    {
        $this->assertNull($this->guideTree()->find('vendor/chief/teksten'));
        $this->assertNull($this->guideTree()->find('vendor'));

        $this->asAdmin()->get('/admin/docs/vendor/chief/teksten')->assertNotFound();
        $this->asAdmin()->get('/admin/docs/vendor/chief/onbestaand')->assertNotFound();
    }
}
