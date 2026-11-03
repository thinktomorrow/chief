<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Application\Admin;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ViewErrorBag;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

final class PageTemplateTest extends ChiefTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->asAdmin();

        view()->share('errors', new ViewErrorBag);
    }

    public function test_it_renders_the_chief_nav_and_a_contained_page_by_default(): void
    {
        $html = Blade::render('<x-chief::page.template title="Pagina">Inhoud</x-chief::page.template>');

        $this->assertStringContainsString('Je gebruikt momenteel Chief versie', $html);
        $this->assertStringContainsString('lg:w-80', $html);
        $this->assertStringContainsString('container min-w-0 grow space-y-6 py-8 max-w-screen-2xl', $html);
        $this->assertStringContainsString('<h1 class="h1 h1-dark text-pretty">Pagina</h1>', $html);
        $this->assertStringContainsString('Inhoud', $html);
    }

    public function test_the_nav_slot_replaces_the_chief_nav_at_the_same_width(): void
    {
        $html = Blade::render("<x-chief::page.template><x-slot name=\"nav\"><p>Eigen navigatie</p></x-slot>\nInhoud</x-chief::page.template>");

        $this->assertStringContainsString('<p>Eigen navigatie</p>', $html);
        $this->assertStringContainsString('lg:w-80', $html);
        $this->assertStringNotContainsString('Je gebruikt momenteel Chief versie', $html);
    }

    public function test_a_full_container_leaves_width_padding_and_header_to_the_page(): void
    {
        $html = Blade::render('<x-chief::page.template title="Pagina" container="full"><article>Inhoud</article></x-chief::page.template>');

        $this->assertStringContainsString('<div class="flex min-w-0 grow flex-col"><article>Inhoud</article></div>', $html);
        $this->assertStringNotContainsString('container min-w-0 grow space-y-6 py-8', $html);
        $this->assertStringNotContainsString('<h1', $html);
    }
}
