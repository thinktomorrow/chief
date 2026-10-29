<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Docs\Tests;

use Illuminate\Http\Request;
use PHPUnit\Framework\Attributes\DataProvider;

class DocsNavTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->useOnlyProjectGuides($this->fixture('sidebar'));
    }

    public function test_the_chief_nav_shows_handleiding_above_instellingen(): void
    {
        $this->asAdmin()->get('/admin')
            ->assertOk()
            ->assertSeeInOrder([
                'href="'.route('chief.docs.index').'"',
                'title="Handleiding"',
                'title="Instellingen"',
            ], false);
    }

    public function test_the_chief_nav_has_no_handleiding_when_every_guide_is_hidden(): void
    {
        config()->set('chief-docs.hidden', ['welkom', 'woordenlijst', 'opleidingen', 'instellingen']);

        $this->asAdmin()->get('/admin')
            ->assertOk()
            ->assertSee('title="Instellingen"', false)
            ->assertDontSee('title="Handleiding"', false);
    }

    public function test_the_chief_nav_has_no_handleiding_when_every_guide_is_restricted_to_other_roles(): void
    {
        $this->useOnlyProjectGuides($this->fixture('admins-only'));

        $this->asAuthor()->get('/admin')
            ->assertOk()
            ->assertDontSee('title="Handleiding"', false);
    }

    public function test_the_chief_nav_shows_handleiding_when_a_restricted_guide_matches_the_admins_role(): void
    {
        $this->useOnlyProjectGuides($this->fixture('admins-only'));

        $this->asAdmin()->get('/admin')
            ->assertOk()
            ->assertSee('title="Handleiding"', false);
    }

    #[DataProvider('docsPaths')]
    public function test_handleiding_is_active_on_the_docs_pages(string $path): void
    {
        $this->assertStringContainsString('rounded-lg bg-grey-100"', $this->renderHelpNavAt($path));
    }

    /**
     * @return array<string, array{string}>
     */
    public static function docsPaths(): array
    {
        return [
            'overview' => ['/admin/docs'],
            'guide' => ['/admin/docs/welkom'],
            'nested guide' => ['/admin/docs/opleidingen/kalender'],
        ];
    }

    public function test_handleiding_is_not_active_outside_the_docs(): void
    {
        $navigation = $this->renderHelpNavAt('/admin');

        $this->assertStringContainsString('title="Handleiding"', $navigation);
        $this->assertStringNotContainsString('rounded-lg bg-grey-100"', $navigation);
    }

    private function renderHelpNavAt(string $path): string
    {
        $this->asAdmin();
        $this->app->instance('request', Request::create($path));

        return view('chief::templates.page.nav.nav-help')->render();
    }
}
