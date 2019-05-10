<?php

namespace Thinktomorrow\Chief\Urls;

use Illuminate\Support\Str;
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

    /**
     * Compose prepend based on the full url. We strip out the
     * slug value but keep the base url segment.
     *
     * @param string $fullUrl
     * @return $this
     */
    public function prepend(string $fullUrl)
    {
        $prepend = str_replace($this->rawSlugValue(), '', $fullUrl);
        $prepend = trim($prepend, '/');

        // If there is a base url segment but the current url record does not contain it yet,
        // we'll need to add it to the prepend right about... now.
        if($this->baseUrlSegment && !$this->endsWithBaseUrlSegment($prepend)){
            $prepend = $prepend .'/'. $this->baseUrlSegment;
        }

        $this->prepend = $prepend .'/';

        return $this;
    }

    public function value()
    {
        return old($this->key, $this->rawSlugValue());
    }

    private function rawSlugValue(): string
    {
        $slug = $this->urlRecord->slug;

        if($this->startsWithBaseUrlSegment($slug)){
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

    private function endsWithBaseUrlSegment($value): bool
    {
        return ($this->baseUrlSegment && Str::endsWith($value, $this->baseUrlSegment));
    }
}