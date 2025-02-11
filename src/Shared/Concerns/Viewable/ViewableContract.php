<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Shared\Concerns\Viewable;

interface ViewableContract
{
    /**
     * Renders the view for this model.
     */
    public function renderView(): string;

    /**
     * This is the model's view identifier. This key is used to determine the full view
     * path of the model. By default this is based on the resourceKey of the model.
     */
    public function viewKey(): string;
}
