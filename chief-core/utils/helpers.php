<?php

/**
 * --------------------------------------------------------------------------
 * Helper: Teaser
 * --------------------------------------------------------------------------
 */
if (!function_exists('teaser')) {
    /**
     * @param $text
     * @param null $max
     * @param null $ending
     * @param string $clean - whitelist of html tags: set to null to allow tags
     * @return mixed|string
     */
    function teaser($text, $max = null, $ending = null, $clean = '')
    {
        if (is_null($max) or is_string($max)) {
            return $text;
        }
        if (!is_null($clean)) {
            $text = cleanupHTML($text, $clean);
        }
        $teaser = substr($text, 0, $max);
        return strlen($text) <= $max ? $teaser : $teaser . $ending;
    }
}


/**
 * --------------------------------------------------------------------------
 * Helper: cleanupString
 * --------------------------------------------------------------------------
 *
 * Takes an input and cleans up a regular string from unwanted input
 *
 * @param 	string 	$value
 * @return 	string
 */
if (!function_exists('cleanupString')) {
    function cleanupString($value)
    {
        $value = strip_tags($value);

        return trim($value);
    }
}

/**
 * --------------------------------------------------------------------------
 * Helper: cleanupHTML
 * --------------------------------------------------------------------------
 *
 * Takes an input and cleans up unwanted / malicious HTML
 *
 * @param 	string 	$value
 * @param 	string 	$whitelist - if false no tagstripping will occur - other than htmLawed
 * @return 	string
 */
if (!function_exists('cleanupHTML')) {
    function cleanupHTML($value, $whitelist = null)
    {
        if (!function_exists('cleanupHTML')) {
            require_once __DIR__ . '/vendors/htmlLawed.php';
        }
        if (is_null($whitelist)) {
            $whitelist = '<code><span><div><label><a><br><p><b><i><del><strike><u><img><video><audio><iframe><object><embed><param><blockquote><mark><cite><small><ul><ol><li><hr><dl><dt><dd><sup><sub><big><pre><code><figure><figcaption><strong><em><table><tr><td><th><tbody><thead><tfoot><h1><h2><h3><h4><h5><h6>';
        }
        // Strip entire blocks of malicious code
        $value = preg_replace(array(
            '@<script[^>]*?>.*?</script>@si',
            '@onclick=[^ ].*? @si'
        ), '', $value);
        // strip unwanted tags via whitelist...
        if (false !== $whitelist) {
            $value = strip_tags($value, $whitelist);
        }
        // cleanup HTML and any unwanted attributes
        $value = htmLawed($value);
        return $value;
    }
}

/**
 * Determine whether current url is the active one
 *
 * @param string $name routename or path without HOST
 * @param array $parameters
 * @return bool
 */
function isActiveUrl($name, $parameters = [])
{
    if (\Illuminate\Support\Facades\Route::currentRouteNamed($name)) {
        $flag = true;
        $current = \Illuminate\Support\Facades\Route::current();

        /**
         * If a single parameter is passed as string, we will convert this to
         * the proper array keyed by the first uri parameter
         */
        if (!is_array($parameters)) {
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
        $pattern = str_replace('\*', '(.*)', preg_quote($name, '#'));
        return !!preg_match("#$pattern#", request()->path());
    }

    return ($name == request()->path());
}

/**
 * Get real path to a versioned asset file.
 * @note: basic logic taken from illuminate/foundation/helpers@elixir
 *
 * @param  string  $file
 * @return string
 *
 * @throws \InvalidArgumentException
 */

if (! function_exists('revasset')) {
    function revasset($file)
    {
        static $manifest = null;

        if (is_null($manifest)) {
            $manifest = json_decode(file_get_contents(public_path('dist/rev-manifest.json')), true);
        }

        if (isset($manifest[$file])) {
            return asset('/dist/'.$manifest[$file]);
        }

        return asset($file);
    }
}


if (!function_exists('addQueryToUrl')) {
    /**
     * Inject a query parameter into an url
     * If the query key already exists, it will be overwritten with the new value
     *
     * @param $url
     * @param array $query_params
     * @param array $overrides
     * @return string
     */
    function addQueryToUrl($url, array $query_params = [], $overrides = [])
    {
        $parsed_url = parse_url($url);

        $parsed_url = array_merge(array_fill_keys([
            'scheme', 'host', 'port', 'path', 'query', 'fragment'
        ], null), $parsed_url, $overrides);

        $scheme = $parsed_url['scheme'] ? $parsed_url['scheme'] . '://' : null;
        $port = $parsed_url['port'] ? ':' . $parsed_url['port'] : null;
        $fragment = $parsed_url['fragment'] ? '#' . $parsed_url['fragment'] : null;

        $baseurl = $scheme . $parsed_url['host'] . $port . $parsed_url['path'];
        $current_query = [];

        $_query = explode('&', $parsed_url['query']);

        array_map(function ($v) use (&$current_query) {
            if (!$v) {
                return;
            }
            $split = explode('=', $v);
            if (count($split) == 2) {
                $current_query[$split[0]] = $split[1];
            }
        }, $_query);

        foreach ($query_params as $key => $value) {
            if (isset($current_query[$key])) {
                unset($current_query[$key]);
            }
        }

        $query = urldecode(http_build_query(array_merge($current_query, $query_params)));

        return $baseurl . '?' . $query . $fragment;
    }
}
