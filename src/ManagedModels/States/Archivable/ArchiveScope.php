<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\States\Archivable;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class ArchiveScope implements Scope
{
    private string $archiveColumn;

    private string $archivedValue;

    public function __construct(string $archiveColumn, string $archivedValue)
    {
        // Constructor can be expanded in the future if needed
        $this->archiveColumn = $archiveColumn;
        $this->archivedValue = $archivedValue;
    }

    public function apply(Builder $builder, Model $model)
    {
        $builder->where($model->getTable().'.'.$this->archiveColumn, '<>', $this->archivedValue);
    }
}
