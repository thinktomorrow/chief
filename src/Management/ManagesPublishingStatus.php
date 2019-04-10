<?php


namespace Thinktomorrow\Chief\Management;

trait ManagesPublishingStatus
{
    public function publicationStatusAsLabel($plain = false)
    {
        if (!$this->isAssistedBy('publish')) {
            return null;
        }

        $label = $this->publicationStatusAsPlainLabel();

        if ($plain) {
            return $label;
        }

        if ($this->assistant('publish')->isPublished()) {
            return '<a href="'.$this->previewUrl().'" target="_blank"><em>'.$label.'</em></a>';
        }

        if ($this->assistant('publish')->isDraft()) {
            return '<a href="'.$this->previewUrl().'" target="_blank" class="text-error"><em>'.$label.'</em></a>';
        }

        return '<span><em>'.$label.'</em></span>';
    }

    private function publicationStatusAsPlainLabel()
    {
        if ($this->assistant('publish')->isPublished()) {
            return 'online';
        }

        if ($this->assistant('publish')->isDraft()) {
            return 'offline';
        }

        if ($this->isAssistedBy('archive') && $this->assistant('archive')->isArchived()) {
            return 'gearchiveerd';
        }


        return '-';
    }
}
