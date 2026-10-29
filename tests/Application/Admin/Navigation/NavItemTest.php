<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Application\Admin\Navigation;

use Illuminate\Support\Facades\Blade;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

final class NavItemTest extends ChiefTestCase
{
    public function test_an_item_keeps_its_regular_spacing_and_active_background(): void
    {
        $html = Blade::render('<x-chief::nav.item label="Pagina\'s" url="/admin/pages" is-active />');

        $this->assertStringContainsString('inline-block w-full text-sm/6 py-1.5', $html);
        $this->assertStringContainsString('bg-grey-100', $html);
        $this->assertStringNotContainsString('before:bg-primary-500', $html);
    }

    public function test_a_compact_item_has_tighter_spacing(): void
    {
        $html = Blade::render('<x-chief::nav.item label="Kalender" url="/admin/docs/kalender" compact />');

        $this->assertStringContainsString('inline-block w-full text-sm/6 py-1"', $html);
        $this->assertStringNotContainsString('py-1.5', $html);
        $this->assertStringNotContainsString('before:bg-primary-500', $html);
    }

    public function test_an_active_compact_item_shows_a_primary_bar_next_to_its_background(): void
    {
        $html = Blade::render('<x-chief::nav.item label="Kalender" url="/admin/docs/kalender" compact is-active />');

        $this->assertStringContainsString('bg-grey-100', $html);
        $this->assertStringContainsString('before:bg-primary-500', $html);
        $this->assertStringContainsString('before:w-0.5', $html);
    }
}
