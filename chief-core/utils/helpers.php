<?php

/**
 * --------------------------------------------------------------------------
 * Helper: Teaser
 * --------------------------------------------------------------------------
 */
if(!function_exists('teaser'))
{
    /**
     * @param $text
     * @param null $max
     * @param null $ending
     * @param string $clean - whitelist of html tags: set to null to allow tags
     * @return mixed|string
     */
    function teaser($text, $max = null, $ending = null, $clean = '')
    {
        if(is_null($max) or is_string($max)) return $text;
        if(!is_null($clean))
        {
            $text = cleanupHTML($text,$clean);
        }
        $teaser = substr($text,0,$max);
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
if(!function_exists('cleanupString'))
{
    function cleanupString( $value )
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
if(!function_exists('cleanupHTML'))
{
    function cleanupHTML( $value, $whitelist = null )
    {
        require_once __DIR__ . '/vendors/htmlLawed.php';

        if(is_null($whitelist))
        {
            $whitelist = '<code><span><div><label><a><br><p><b><i><del><strike><u><img><video><audio><iframe><object><embed><param><blockquote><mark><cite><small><ul><ol><li><hr><dl><dt><dd><sup><sub><big><pre><code><figure><figcaption><strong><em><table><tr><td><th><tbody><thead><tfoot><h1><h2><h3><h4><h5><h6>';
        }
        // Strip entire blocks of malicious code
        $value = preg_replace(array(
            '@<script[^>]*?>.*?</script>@si',
            '@onclick=[^ ].*? @si'
        ),'',$value);
        // strip unwanted tags via whitelist...
        if(false !== $whitelist) $value = strip_tags($value, $whitelist);
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
    if(\Illuminate\Support\Facades\Route::currentRouteNamed($name))
    {
        $flag = true;
        $current = \Illuminate\Support\Facades\Route::current();

        /**
         * If a single parameter is passed as string, we will convert this to
         * the proper array keyed by the first uri parameter
         */
        if(!is_array($parameters))
        {
            $names = $current->parameterNames();
            $parameters = [reset($names) => $parameters];
        }

        foreach($parameters as $key => $parameter)
        {
            if($current->parameter($key,false) != $parameter)
            {
                $flag = false;
            }
        }

        return $flag;
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
