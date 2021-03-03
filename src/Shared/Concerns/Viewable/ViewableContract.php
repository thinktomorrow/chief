<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Shared\Concerns\Viewable;

use Thinktomorrow\Chief\PageBuilder\Relations\ActsAsParent;

interface ViewableContract
{
    /**
     * This is the model's view identifier. This key is used to determine the full view
     * path of the model. By default this is based on the morphKey value of the model.
     *
     * @return string
     */
    public function viewKey(): string;

    /**
     * This is the model's identifier for set grouping. This is only applicable to pages.
     * Modules will always be rendered on their own
     *
     * @return string
     */
    public function setKey(): string;

    /**
     * Renders the view for this model.
     *
     * @return string
     */
    public function renderView(): string;

    /**
     * Sets the models parent for the current view path and rendering.
     * In case the model is rendered within a related parent view, this affects
     * the way the view is determined as well as the passed data parameters.
     *
     * @param ActsAsParent $parent
     *
     * @return self
     */
    public function setViewParent(ActsAsParent $parent): self;
}
