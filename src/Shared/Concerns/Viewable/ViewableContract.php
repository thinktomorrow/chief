<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Shared\Concerns\Viewable;

interface ViewableContract
{
    /**
     * Renders the view for this model.
     *
     * @return string
     */
    public function renderView(): string;

    /**
     * The view key identifies the view file for this model.
     * By default this will be the managedModelKey but
     * this can basically be any key.
     *
     * @return string
     */
    public function viewKey(): string;
}
