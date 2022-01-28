<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Admin\Users;

use Illuminate\Session\Store;
use Illuminate\Support\Str;
use Thinktomorrow\Url\Url;

class VisitedUrl
{
    private Store $session;
    private static string $session_key = 'visited_urls';

    public function __construct(Store $session)
    {
        $this->session = $session;
    }

    /**
     * Allows to keep the last filtered versions of index pages so when the admin goes back to the index, this page
     * loads with the last used filters. This adheres to the going back mentality coming from a detail page.
     *
     * The reference is the original url of a page, which is used to track down the visitor's specific url.
     * The url is the altered version of the url, mostly filtered, sorted or paginated.
     */
    public function add(string $reference, string $url = null): void
    {
        // With only one argument given, we assume the specific url is passed, so we clean it up to use as our base reference.
        if (! $url) {
            $url = $reference;
            $reference = Url::fromString($reference)->getScheme().'://'.
                Url::fromString($reference)->getHost().
                Url::fromString($reference)->getPath();
        }
        $this->session->put(static::$session_key.'.'.$this->keyify($reference), $url);
    }

    public function get(string $reference): string
    {
        return $this->session->get(static::$session_key.'.'.$this->keyify($reference), $reference);
    }

    private function keyify(string $key): string
    {
        return Str::slug($key);
    }
}
