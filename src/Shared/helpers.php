<?php

/**
 * Wrapper around the dd helper from Symfony. This function provides the file from where the
 * dd function has been called so you won't be in the dark when finding it again.
 */
if (! function_exists('trap')) {
    function trap($var, ...$moreVars): void
    {
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1);
        $basePath = __DIR__.'/../../';

        if ('cli' == php_sapi_name()) {
            print_r("\e[1;30m dumped at: ".str_replace($basePath, '', $trace[0]['file']).', line: '.$trace[0]['line']."\e[40m\n");
        } else {
            print_r('[dumped at: '.str_replace($basePath, '', $trace[0]['file']).', line: '.$trace[0]['line']."]\n");
        }

        dd($var, ...$moreVars);
    }
}

// Retrieve the logged in admin
if (! function_exists('chiefAdmin')) {
    function chiefAdmin(): ?Illuminate\Contracts\Auth\Authenticatable
    {
        return \Illuminate\Support\Facades\Auth::guard('chief')->user();
    }
}

// Retrieve the online fragments of the current owning model
if (! function_exists('getFragments')) {
    function getFragments($owner): Illuminate\Support\Collection
    {
        return app(\Thinktomorrow\Chief\Fragments\FragmentsRenderer::class)->getFragments($owner);
    }
}

/*
 * Retrieve the public asset with a version stamp.
 * This allows for browsercache out of the box
 */
if (! function_exists('chief_cached_asset')) {
    function chief_cached_asset($filepath): string
    {
        $manifestPath = '/chief-assets/back';

        // Manifest expects each entry to start with a leading slash - we make sure to deduplicate the manifest path.
        $entry = str_replace($manifestPath, '', '/'.ltrim($filepath, '/'));

        try {
            // Paths should be given relative to the manifestpath so make sure to remove the basepath
            return asset(mix($entry, $manifestPath));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error($e);

            return $manifestPath.$entry;
        }
    }
}

if (! function_exists('chiefSetting')) {
    function chiefSetting($key = null, $locale = null, $default = null)
    {
        $settings = app(\Thinktomorrow\Chief\Admin\Settings\Settings::class);

        if (is_null($key)) {
            return $settings;
        }

        return $settings->get($key, $locale, $default);
    }
}

// global access to Register singleton
if (! function_exists('chiefRegister')) {
    function chiefRegister()
    {
        return app(\Thinktomorrow\Chief\Managers\Register\Register::class);
    }
}

if (! function_exists('chiefmenu')) {
    /**
     * @param mixed $key
     *
     * @return \Thinktomorrow\Chief\Site\Menu\Menu|\Thinktomorrow\Chief\Site\Menu\NullMenu
     */
    function chiefmenu($key = 'main')
    {
        $menu = \Thinktomorrow\Chief\Site\Menu\Menu::find($key);

        return $menu ?? new \Thinktomorrow\Chief\Site\Menu\NullMenu();
    }
}

// Retrieve the logged in admin
if (! function_exists('visitedUrl')) {
    function visitedUrl(string $url): string
    {
        return app(\Thinktomorrow\Chief\Admin\Users\VisitedUrl::class)->get($url);
    }
}

if (! function_exists('str_slug_slashed')) {
    function str_slug_slashed($title, $separator = '-', $language = 'en'): string
    {
        $parts = explode('/', $title);

        foreach ($parts as $i => $part) {
            $parts[$i] = Illuminate\Support\Str::slug($part, $separator, $language);
        }

        return implode('/', $parts);
    }
}

if (! function_exists('is_array_empty')) {
    function is_array_empty(array $values): bool
    {
        $empty = true;

        foreach ($values as $value) {
            if (! $value || ! trim($value)) {
                continue;
            }
            $empty = false;
        }

        return $empty;
    }
}

if (! function_exists('contract')) {
    function contract($instance, $contract): bool
    {
        return $instance instanceof $contract;
    }
}

/*
 * This function checks if the given method exists on the class AND that this method is available
 * in the public api. method_exists also checks the existence of private methods so we'll
 * need an extra assurance that the method has in fact public accessibility.
 */
if (! function_exists('public_method_exists')) {
    function public_method_exists($class, $method): bool
    {
        $reflection = new ReflectionClass($class);

        if ($reflection->hasMethod($method)) {
            return $reflection->getMethod($method)->isPublic();
        }

        return false;
    }
}

/*
 * --------------------------------------------------------------------------
 * Helper: Teaser
 * --------------------------------------------------------------------------
 */
if (! function_exists('teaser')) {
    /**
     * @param $text
     * @param null   $max
     * @param null   $ending
     * @param string $clean  - whitelist of html tags: set to null to allow tags
     *
     * @return mixed|string
     */
    function teaser($text, $max = null, $ending = null, $clean = '')
    {
        if (is_null($max) or is_string($max)) {
            return $text;
        }

        if (! is_null($clean)) {
            $text = cleanupHTML($text, $clean);
        }

        $teaser = mb_substr($text, 0, $max, 'utf-8');

        return strlen($text) <= $max ? $teaser : $teaser.$ending;
    }
}

/*
 * --------------------------------------------------------------------------
 * Helper: cleanupString
 * --------------------------------------------------------------------------
 *
 * Takes an input and cleans up a regular string from unwanted input
 *
 * @param string $value
 * @return    string
 */
if (! function_exists('cleanupString')) {
    function cleanupString($value): string
    {
        $value = strip_tags($value);

        return trim($value);
    }
}

/*
 * --------------------------------------------------------------------------
 * Helper: cleanupHTML
 * --------------------------------------------------------------------------
 *
 * Takes an input and cleans up unwanted / malicious HTML
 *
 * @param string $value
 * @param string $whitelist - if false no tagstripping will occur - other than HTMLPurifier
 * @return    string
 */
if (! function_exists('cleanupHTML')) {
    function cleanupHTML($value, $whitelist = null): string
    {
        if (is_null($whitelist)) {
            $whitelist = '<code><span><div><label><a><br><p><b><i><del><strike><u><img><video><audio><iframe><object><embed><param><blockquote><mark><cite><small><ul><ol><li><hr><dl><dt><dd><sup><sub><big><pre><code><figure><figcaption><strong><em><table><tr><td><th><tbody><thead><tfoot><h1><h2><h3><h4><h5><h6>';
        }
        // Strip entire blocks of malicious code
        $value = preg_replace([
            '@<script[^>]*?>.*?</script>@si',
            '@onclick=[^ ].*? @si',
        ], '', $value);
        // strip unwanted tags via whitelist...
        if (false !== $whitelist) {
            $value = strip_tags($value, $whitelist);
        }

        return $value;
    }
}

/*
 * Determine whether current url is the active one
 *
 * @param string $name routename or path without HOST
 * @param array $parameters
 * @return bool
 */
if (! function_exists('isActiveUrl')) {
    function isActiveUrl($name, $parameters = []): bool
    {
        if (\Illuminate\Support\Facades\Route::currentRouteNamed($name)) {
            $flag = true;
            $current = \Illuminate\Support\Facades\Route::current();

            /*
             * If a single parameter is passed as string, we will convert this to
             * the proper array keyed by the first uri parameter
             */
            if (! is_array($parameters)) {
                $names = $current->parameterNames();
                $parameters = [reset($names) => $parameters];
            }

            foreach ($parameters as $key => $parameter) {
                if ($current->parameter($key, false) != $parameter) {
                    $flag = false;
                }
            }

            return $flag;
        }

        $name = ltrim($name, '/');

        if (false !== strpos($name, '*')) {
            $name = str_replace(request()->getSchemeAndHttpHost().'/', '', $name);
            $pattern = str_replace('\*', '(.*)', preg_quote($name, '#'));

            return (bool) preg_match("#{$pattern}#", request()->path());
        }

        $url = \Thinktomorrow\Url\Url::fromString(request()->fullUrl());
        $fullUrlWithoutQuery = $url->getScheme().'://'.$url->getHost().'/'.$url->getPath();

        return request()->is($name) || $name == request()->path() || $name == request()->fullUrl() || $name == $fullUrlWithoutQuery;
    }
}

/*
 * Inject a query parameter into an url
 * If the query key already exists, it will be overwritten with the new value
 *
 * @param $url
 * @param array $query_params
 * @param array $overrides
 * @return string
 */
if (! function_exists('addQueryToUrl')) {
    function addQueryToUrl($url, array $query_params = [], $overrides = []): string
    {
        $parsed_url = parse_url($url);

        $parsed_url = array_merge(array_fill_keys([
            'scheme',
            'host',
            'port',
            'path',
            'query',
            'fragment',
        ], null), $parsed_url, $overrides);

        $scheme = $parsed_url['scheme'] ? $parsed_url['scheme'].'://' : null;
        $port = $parsed_url['port'] ? ':'.$parsed_url['port'] : null;
        $fragment = $parsed_url['fragment'] ? '#'.$parsed_url['fragment'] : null;

        $baseurl = $scheme.$parsed_url['host'].$port.$parsed_url['path'];
        $current_query = [];

        $_query = explode('&', $parsed_url['query']);

        array_map(function ($v) use (&$current_query) {
            if (! $v) {
                return;
            }
            $split = explode('=', $v);
            if (2 == count($split)) {
                $current_query[$split[0]] = $split[1];
            }
        }, $_query);

        foreach (array_keys($query_params) as $key) {
            if (isset($current_query[$key])) {
                unset($current_query[$key]);
            }
        }

        $query = urldecode(http_build_query(array_merge($current_query, $query_params)));

        return $baseurl.'?'.$query.$fragment;
    }
}

if (! function_exists('chiefMemoize')) {
    /**
     * Memoize a function.
     *
     * @param $key
     *
     * @return mixed
     */
    function chiefMemoize($key, Closure $closure, array $parameters = [])
    {
        return (new \Thinktomorrow\Chief\Shared\Helpers\Memoize($key))->run($closure, $parameters);
    }
}
