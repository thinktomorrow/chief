<?php

namespace Thinktomorrow\Chief\Urls;

use Thinktomorrow\Chief\Fields\Types\InputField;

class UrlSlugField extends InputField
{
    private $urlRecord;

    private $baseUrlSegment;

    public function setUrlRecord(UrlRecord $urlRecord)
    {
        $this->urlRecord = $urlRecord;

        return $this;
    }

    public function setBaseUrlSegment($baseUrlSegment = null)
    {
        $this->baseUrlSegment = $baseUrlSegment;

        return $this;
    }

    public function value()
    {
        return old($this->key, $this->rawSlugValue());
    }

    private function rawSlugValue(): string
    {
        if (!$this->urlRecord) {
            return '';
        }

        $slug = $this->urlRecord->slug;

        if ($this->startsWithBaseUrlSegment($slug)) {
            $slug = trim(substr($slug, strlen($this->baseUrlSegment)), '/');
        }

        return $slug;
    }

    /**
     * @param $value
     * @return bool
     */
    private function startsWithBaseUrlSegment($value): bool
    {
        return ($this->baseUrlSegment && 0 === strpos($value, $this->baseUrlSegment));
    }

    public function toArray(): array
    {
        return array_merge($this->values, [
            'key' => $this->key,
            'prepend' => $this->prepend,
            'label' => $this->label,
            'placeholder' => $this->placeholder,
            'description' => $this->description,
            'value' => $this->value(),
            'locale' => $this->locale,
            'hint' => null, // Hint placeholder to show url hint when it already exists
        ]);
    }
}
