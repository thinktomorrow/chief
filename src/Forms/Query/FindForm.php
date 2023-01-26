<?php

namespace Thinktomorrow\Chief\Forms\Query;

use Thinktomorrow\Chief\Forms\Form;
use Thinktomorrow\Chief\Forms\Forms;
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

        return Forms::make($resource->fields($model))->fill($manager, $model)->find($formId);
    }
}
