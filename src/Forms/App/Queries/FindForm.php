<?php

namespace Thinktomorrow\Chief\Forms\App\Queries;

use Thinktomorrow\Chief\Forms\Layouts\Form;
use Thinktomorrow\Chief\Forms\Layouts\PageLayout;
use Thinktomorrow\Chief\Managers\Register\Registry;

class FindForm
{
    private Registry $registry;

    public function __construct(Registry $registry)
    {
        $this->registry = $registry;
    }

    public function findByModel($model, string $formId): Form
    {
        $resource = $this->registry->findResourceByModel($model::class);
        $manager = $this->registry->findManagerByModel($model::class);

        return PageLayout::make($resource->fields($model))->fill($manager, $model)->findForm($formId);
    }
}
