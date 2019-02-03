<?php


namespace Thinktomorrow\Chief\Management;

use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Audit\Audit;

trait ManagesArchiving
{
    public function isArchived(): bool
    {
        return $this->model->isArchived();
    }

    public function archive()
    {
        $this->model->archive();

        Audit::activity()
            ->performedOn($this->model)
            ->log('archived');
    }

    public function unarchive()
    {
        $this->model->unarchive();

        Audit::activity()
            ->performedOn($this->model)
            ->log('unarchived');
    }

    public function findAllArchived(): Collection
    {
        return $this->model->archived()->get();
    }
}
