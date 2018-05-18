<?php

namespace Chief\Common\Relations;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as StandardCollection;

interface ActsAsParent
{
    public function children(): Collection;

    public function adoptChild(ActsAsChild $child, array $attributes = []);

    public function rejectChild(ActsAsChild $child);

    public function presentForChild(ActsAsChild $child, Relation $relation): string;

    public function presentChildren(): StandardCollection;

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

    public function relationWithChild(ActsAsChild $child): Relation;
}