<?php

namespace Chief\Common\Traits;

trait Archivable
{
    public function isArchived()
    {
        return !is_null($this->{$this->getArchivedAtColumn()});
    }

    public function scopeArchived($query)
    {
        $query->whereNotNull($this->getQualifiedArchivedAtColumn());
    }

    public function scopeUnarchived($query)
    {
        $query->whereNull($this->getQualifiedArchivedAtColumn());
    }

    public function archive()
    {
        $query = $this->newQueryWithoutScopes()->where($this->getKeyName(), $this->getKey());

        $time = $this->freshTimestamp();

        $columns = [$this->getArchivedAtColumn() => $this->fromDateTime($time)];

        $this->{$this->getArchivedAtColumn()} = $time;

        if ($this->timestamps && !is_null($this->getUpdatedAtColumn())) {
            $this->{$this->getUpdatedAtColumn()} = $time;

            $columns[$this->getUpdatedAtColumn()] = $this->fromDateTime($time);
        }

        $query->update($columns);
    }

    public function unarchive()
    {
        $this->{$this->getArchivedAtColumn()} = null;

        $result = $this->save();

        return $result;
    }

    /**
     * Get the name of the "Archived at" column.
     *
     * @return string
     */
    public function getArchivedAtColumn()
    {
        return defined('static::ARCHIVED_AT') ? static::ARCHIVED_AT : 'archived_at';
    }

    /**
     * Get the fully qualified "Archived at" column.
     *
     * @return string
     */
    public function getQualifiedArchivedAtColumn()
    {
        return $this->qualifyColumn($this->getArchivedAtColumn());
    }
}