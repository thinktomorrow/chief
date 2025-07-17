<?php

namespace Thinktomorrow\Chief\Forms\Layouts;

interface Layout
{
    /**
     * Component key to identify it alongside
     * the other components on the page
     */
    public function getKey(): string;

    /**
     * Get the position of the component. This is used to determine
     * where the component should be rendered in the layout.
     * If no position is set, it defaults to 'main'.
     */
    public function getPosition(): ?string;
}
