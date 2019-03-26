<?php

namespace Thinktomorrow\Chief\Concerns\Publishable;

trait Publishable
{
    public function isPublished()
    {
        return (!!$this->published);
    }

    public function isDraft()
    {
        return (!$this->published);
    }

    public function scopePublished($query)
    {
        // Here we widen up the results in case of preview mode and ignore the published scope
        if (PreviewMode::fromRequest()->check()) {
            return;
        }

        $query->where('published', 1);
    }

    public function scopeDrafted($query)
    {
        $query->where('published', 0);
    }

    public function publish()
    {
        $this->published = 1;
        $this->save();
    }

    public function draft()
    {
        $this->published = 0;
        $this->save();
    }

    public static function getAllPublished()
    {
        return self::published()->get();
    }

    public function scopeSortedByPublished($query)
    {
        return $query->orderBy('published', 'DESC');
    }

    public function publicationStatusAsLabel($plain = false)
    {
        $label = $this->publicationStatusAsPlainLabel();

        if ($plain) {
            return $label;
        }

        if ($this->isPublished()) {
            return '<a href="'.$this->previewUrl().'" target="_blank"><em>'.$label.'</em></a>';
        }

        if ($this->isDraft()) {
            return '<a href="'.$this->previewUrl().'" target="_blank" class="text-error"><em>'.$label.'</em></a>';
        }

        return '<span><em>'.$label.'</em></span>';
    }

    private function publicationStatusAsPlainLabel()
    {
        if ($this->isPublished()) {
            return 'online';
        }

        if ($this->isDraft()) {
            return 'offline';
        }

        if ($this->isAssistedBy('archive') && $this->assistant('archive')->isArchived()) {
            return 'gearchiveerd';
        }


        return '-';
    }
}
