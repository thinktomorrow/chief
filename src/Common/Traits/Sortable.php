<?php

namespace Thinktomorrow\Chief\Common\Traits;

trait Sortable
{
    protected $sortableattribute = 'sequence';

    public function scopeSequence($query)
    {
        return $query->orderBy($this->sortableattribute, 'ASC');
    }

    /**
     * Order of array represents the new sequence
     * Values are the id's of the service pages to be ordered against each other.
     *
     * @param array $sequence
     */
    public static function reorderAgainstSiblings(array $sequence)
    {
        array_walk($sequence, function ($id, $i) {
            self::findOrFail($id)->reorder($i);
        });
    }

    public function reorder($sequence)
    {
        $this->{$this->sortableattribute} = $sequence;
        $this->save();
    }
}
