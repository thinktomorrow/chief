<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Unit;

use Livewire\Attributes\Renderless;
use ReflectionMethod;
use Thinktomorrow\Chief\Assets\UI\Livewire\AssetGallery;
use Thinktomorrow\Chief\Forms\UI\Livewire\ModelForm;
use Thinktomorrow\Chief\Fragments\UI\Livewire\FragmentContext;
use Thinktomorrow\Chief\Fragments\UI\Livewire\FragmentContexts;
use Thinktomorrow\Chief\ManagedModels\States\UI\Livewire\ModelState;
use Thinktomorrow\Chief\Menu\UI\Livewire\MenuList;
use Thinktomorrow\Chief\Sites\UI\Livewire\ModelSiteSelection;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Urls\UI\Livewire\ModelLinks;

final class LivewireRenderlessActionsTest extends ChiefTestCase
{
    public function test_dispatch_only_actions_are_renderless(): void
    {
        $actions = [
            [AssetGallery::class, 'openAssetEdit'],
            [AssetGallery::class, 'openFileUpload'],
            [AssetGallery::class, 'deleteAsset'],
            [ModelForm::class, 'editForm'],
            [MenuList::class, 'addItem'],
            [MenuList::class, 'editItem'],
            [FragmentContexts::class, 'addItem'],
            [FragmentContexts::class, 'editItem'],
            [FragmentContext::class, 'editFragment'],
            [FragmentContext::class, 'addFragment'],
            [ModelState::class, 'edit'],
            [ModelSiteSelection::class, 'edit'],
            [ModelLinks::class, 'edit'],
        ];

        foreach ($actions as [$component, $method]) {
            $attributes = (new ReflectionMethod($component, $method))->getAttributes(Renderless::class);

            $this->assertCount(1, $attributes, "Action [{$component}::{$method}] should be renderless.");
        }
    }
}
