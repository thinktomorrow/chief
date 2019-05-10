<?php

namespace Thinktomorrow\Chief\Urls\ProvidesUrl;

interface ProvidesUrl
{
    /**
     * Full url to be used in frontend navigation
     *
     * @param null|string $locale
     * @return string
     */
    public function url($locale = null): string;

    /**
     * Same as url() but used for admins to allow them to view drafted urls.
     *
     * @param null|string $locale
     * @return string
     */
    public function previewUrl($locale = null): string;

    /**
     * The base category uri segment for this type of model.
     * e.g. /news/, /events/.
     *
     * @param null|string $locale
     * @return string
     */
    public static function baseUrlSegment($locale = null): string;
}
