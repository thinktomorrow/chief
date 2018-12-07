<?php

/**
 * Retrieve the logged in admin
 */
if (!function_exists('admin')) {
    function admin()
    {
        return \Illuminate\Support\Facades\Auth::guard('chief')->user();
    }
}

/**
 * Form fields for honeypot protection on form submissions
 */
if (!function_exists('honeypot_fields')) {
    function honeypot_fields()
    {
        return '<div style="display:none;"><input type="text" name="your_name"/><input type="hidden" name="_timer" value="'.time().'" /></div>';
    }
}



/**
 * Retrieve the public asset with a version stamp.
 * This allows for browsercache out of the box
 */
if (!function_exists('chief_cached_asset')) {
    function chief_cached_asset($filepath)
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

if (!function_exists('chiefSetting')) {
    function chiefSetting($key = null, $default = null)
    {
        $manager = app(\Thinktomorrow\Chief\Settings\SettingsManager::class);

        if (is_null($key)) {
            return $manager;
        }
        
        return $manager->get($key, $default);
    }
}

if (!function_exists('chiefmenu')) {
    function chiefmenu($key = 'main')
    {
        $menu = \Thinktomorrow\Chief\Menu\Menu::find($key);

        return $menu ?? new \Thinktomorrow\Chief\Menu\NullMenu();
    }
}

if (!function_exists('str_slug_slashed')) {
    function str_slug_slashed($title, $separator = '-', $language = 'en')
    {
        $parts = explode('/', $title);

        foreach ($parts as $i => $part) {
            $parts[$i] = str_slug($part, $separator, $language);
        }

        return implode('/', $parts);
    }
}

if (!function_exists('is_array_empty')) {
    function is_array_empty(array $values)
    {
        $empty = true;

        foreach ($values as $value) {
            if (! $value || !trim($value)) {
                continue;
            }
            $empty = false;
        }

        return $empty;
    }
}

if (! function_exists('contract')) {
    function contract($instance, $contract)
    {
        return $instance instanceof $contract;
    }
}

if (! function_exists('isManagerThatPublishes')) {
    function isManagerThatPublishes($class)
    {

        return contract($class, \Thinktomorrow\Chief\Management\ManagerThatPublishes::class);
    }
}


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

        // Undo the encoding performed by htmlLawed.
        $value = str_replace('&amp;', '&', $value);

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
