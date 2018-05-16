<?php

namespace Chief\Common\Relations;

use Illuminate\Database\Eloquent\Collection;

interface ActsAsChild
{
    public function parents(): Collection;

    public function acceptParent(ActsAsParent $parent, array $attributes = []);

    public function rejectParent(ActsAsParent $parent);

    public function presentForParent(ActsAsParent $parent, Relation $relation): string;

    /**
     * Composite key consisting of the type of class combined with the
     * model id. Both are joined with an @ symbol. This is used as
     * identifier of the relation mostly as form values.
     *
     * @return string
     */
    public function getRelationId(): string;

    /**
     * Label that identifies the relation class for an user. This
     * is mostly used in the interface of the admin panel.
     *
     * @return string
     */
    public function getRelationLabel(): string;

    public function getRelationGroup(): string;

    public function relationWithParent(ActsAsParent $parent): Relation;
}