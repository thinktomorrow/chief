<?php

use Illuminate\Support\HtmlString;

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
    /**
     * @return \Illuminate\Support\HtmlString
     */
    function honeypot_fields()
    {
        return new HtmlString('<div style="display:none;"><input type="text" name="your_name"/><input type="hidden" name="_timer" value="'.time().'" /></div>');
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

if(!function_exists('ddd')){
    function ddd($var, ...$moreVars){
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1);
        if(php_sapi_name() == 'cli'){
            print_r("\e[1;30m dumped at: " . str_replace(base_path(),'', $trace[0]['file']). ", line: " . $trace[0]['line'] . "\e[40m\n");
        } else{
            print_r("[dumped at: " . str_replace(base_path(),'', $trace[0]['file']). ", line: " . $trace[0]['line'] . "]\n");
        }
        return dd($var, ...$moreVars);
    }
}
