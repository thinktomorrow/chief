<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Application\Admin\Navigation;

use Illuminate\View\ComponentAttributeBag;
use Thinktomorrow\Chief\Admin\Nav\Nav;
use Thinktomorrow\Chief\Admin\Nav\NavItem;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

final class SettingsNavigationTest extends ChiefTestCase
{
    public function test_it_includes_tagged_settings_navigation_items(): void
    {
        resolve(Nav::class)
            ->add(new NavItem('Custom settings', '/admin/custom-settings', ['nav-settings'], ''))
            ->add(new NavItem('Other navigation', '/admin/other', ['nav-other'], ''));

        $this->asAdmin();

        $navigation = $this->renderSettingsNavigation();

        $this->assertStringContainsString('Custom settings', $navigation);
        $this->assertStringNotContainsString('Other navigation', $navigation);
    }

    public function test_it_only_includes_tagged_settings_navigation_items_for_authorized_admins(): void
    {
        resolve(Nav::class)->add(new NavItem('Custom settings', '/admin/custom-settings', ['nav-settings'], ''));

        $this->asAdminWithoutRole();

        $navigation = $this->renderSettingsNavigation();

        $this->assertStringNotContainsString('Custom settings', $navigation);
    }

    private function renderSettingsNavigation(): string
    {
        return view('chief::templates.page.nav.nav-settings', [
            'attributes' => new ComponentAttributeBag,
        ])->render();
    }
}
