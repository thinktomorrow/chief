<?php

namespace Thinktomorrow\Chief\Plugins\TimeTable\Infrastructure\Models;

trait UsesContent
{
    public function setContentAttribute($value)
    {
        if (! is_array($value)) {
            $value = [app()->getLocale() => $value];
        }

        foreach ($value as $locale => $content) {
            $this->setContent($content, $locale);
        }
    }

    public function getContentAttribute()
    {
        return $this->getContent(app()->getLocale());
    }


    public function getContent(string $locale): ?string
    {
        return $this->getData('content', $locale);
    }

    public function setContent(?string $content, string $locale): void
    {
        $copy = $this->data;
        data_set($copy, 'content.'.$locale, $content);

        $this->data = array_merge($this->data ?? [], $copy);
    }

    private function getData(string $key, string $index = null, $default = null)
    {
        $key = $index ? $key .'.'.$index : $key;

        return data_get($this->data, $key, $default);
    }
}
