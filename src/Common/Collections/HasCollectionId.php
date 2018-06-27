<?php

namespace Thinktomorrow\Chief\Common\Collections;

interface HasCollectionId
{
    /**
     * Composite key consisting of the type of class combined with the
     * model id. Both are joined with an @ symbol. This is used as
     * identifier of the relation mostly as form values.
     *
     * @return CollectionId
     */
    public function getCollectionId(): CollectionId;

    /**
     * Label that identifies the relation class for an user. This
     * is mostly used in the interface of the admin panel.
     *
     * @return string
     */
    public function getCollectionLabel(): string;

    /**
     * Label that identifies the relation group. This
     * is used in the interface of the admin panel to group relations together.
     *
     * @return string
     */
    public function getCollectionGroup(): string;
}