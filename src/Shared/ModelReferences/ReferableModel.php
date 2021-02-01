<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Shared\ModelReferences;

interface ReferableModel
{
    /**
     * Composite key consisting of the type of class combined with the
     * model id. Both are joined with an @ symbol. This is used as
     * identifier of the instance mostly in selections.
     */
    public function modelReference(): ModelReference;

    /**
     * Label that identifies the model reference with a human readable string.
     * This is mostly used in the interface of the admin panel.
     *
     * @return string
     */
    public function modelReferenceLabel(): string;

    /**
     * Label that identifies the grouping under which this reference should belong.
     * This is a categorization used to group select options and other listings.
     * It also combines similar models together in the view rendering.
     *
     * @return string
     */
    public function modelReferenceGroup(): string;
}
