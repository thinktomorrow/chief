<?php


namespace Thinktomorrow\Chief\Management;

trait ManagesPublishing
{
    public function isPublished(): bool
    {
        return $this->model->isPublished();
    }

    public function isDraft(): bool
    {
        return $this->model->isDraft();
    }

    public function publish()
    {
        $this->model->publish();
    }

    public function draft()
    {
        $this->model->draft();
    }

    public function publicationStatusAsLabel($plain = false)
    {
        $label = $this->publicationStatusAsPlainLabel();

        if($plain) return $label;

        if ($this->model->isPublished()) {
            return '<a href="'.$this->model->menuUrl().'" target="_blank"><em>'.$label.'</em></a>';
        }

        if ($this->model->isDraft()) {
            return '<a href="'.$this->model->previewUrl().'" target="_blank" class="text-error"><em>'.$label.'</em></a>';
        }

        return '<span><em>'.$label.'</em></span>';
    }

    private function publicationStatusAsPlainLabel()
    {
        if ($this->model->isPublished()) {
            return 'online';
        }

        if ($this->model->isDraft()) {
            return 'offline';
        }

        if ($this->model->isArchived()) {
            return 'gearchiveerd';
        }

        return '-';
    }
}