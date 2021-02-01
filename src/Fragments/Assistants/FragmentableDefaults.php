<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\Assistants;

use Thinktomorrow\Chief\Fragments\Fragmentable;
use Thinktomorrow\Chief\Fragments\Database\FragmentModel;
use Thinktomorrow\Chief\Shared\Concerns\Viewable\Viewable;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModelDefault;
use Thinktomorrow\Chief\ManagedModels\Assistants\ManagedModelDefaults;

trait FragmentableDefaults
{
    use ReferableModelDefault;
    use ManagedModelDefaults;
    use Viewable;

    private FragmentModel $fragmentModel;

    public function renderAdminFragment($owner, $loop, $fragments)
    {
        return $this->renderFragment($owner, $loop, $fragments, []);
    }

    public function renderFragment($owner, $loop, $fragments, $viewData): string
    {
        // Default (legacy) view rendering
        if(public_method_exists($this, 'renderView')) {
            return $this->renderView();
        }

        return '';
    }

    public function setFragmentModel(FragmentModel $fragmentModel): Fragmentable
    {
        $this->fragmentModel = $fragmentModel;

        return $this;
    }

    public function fragmentModel(): FragmentModel
    {
        if(!isset($this->fragmentModel)) {
            throw new \DomainException('FragmentModel property on ['.get_class($this).'] expected to be set, but it\'s not.');
        }

        return $this->fragmentModel;
    }
}
