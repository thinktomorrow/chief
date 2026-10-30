<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Docs\Tests;

use InvalidArgumentException;

class HiddenGuidesTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->useProjectGuides($this->fixture('project'));

        config()->set('chief-docs.hidden', ['opleidingen', 'chief::paginas/status']);
    }

    public function test_a_hidden_guide_returns_a_404(): void
    {
        $this->asAdmin()->get('/admin/docs/chief::paginas/status')->assertNotFound();
        $this->asAdmin()->get('/admin/docs/chief::paginas/links')->assertOk();
    }

    public function test_every_guide_beneath_a_hidden_guide_returns_a_404(): void
    {
        $this->asAdmin()->get('/admin/docs/opleidingen')->assertNotFound();
        $this->asAdmin()->get('/admin/docs/opleidingen/inschrijvingen')->assertNotFound();
        $this->asAdmin()->get('/admin/docs/verplaatst')->assertNotFound();
    }

    public function test_hidden_guides_are_absent_from_the_listing(): void
    {
        $this->asAdmin()->get('/admin/docs')
            ->assertOk()
            ->assertSee($this->guideHref('chief::paginas/links'), false)
            ->assertDontSee($this->guideHref('chief::paginas/status'), false)
            ->assertDontSee($this->guideHref('opleidingen'), false)
            ->assertDontSee($this->guideHref('opleidingen/inschrijvingen'), false)
            ->assertDontSee($this->guideHref('verplaatst'), false);
    }

    public function test_hidden_guides_are_absent_from_the_visible_tree(): void
    {
        $tree = $this->guideTree();

        $this->assertNull($tree->find('opleidingen'));
        $this->assertNull($tree->find('verplaatst'));
        $this->assertNull($tree->find('chief::paginas/status'));
        $this->assertNotNull($tree->find('chief::paginas'));
    }

    public function test_a_hidden_entry_that_is_not_a_guide_id_throws(): void
    {
        config()->set('chief-docs.hidden', ['opleidingen', ['chief::paginas']]);

        $this->withoutExceptionHandling();
        $this->expectException(InvalidArgumentException::class);

        $this->asAdmin()->get('/admin/docs');
    }

    private function guideHref(string $guideId): string
    {
        return 'href="'.route('chief.docs.show', $guideId).'"';
    }
}
