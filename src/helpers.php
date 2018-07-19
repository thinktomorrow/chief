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
if (!function_exists('cached_asset')) {
    function cached_asset($filepath, $type = null)
    {
        $manifestPath = $type == 'back' ? '/chief-assets/back' : '/assets';

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
    function chiefSetting($key = null, $default = null) {

        $manager = app(\Thinktomorrow\Chief\Settings\SettingsManager::class);

        if(is_null($key)) {
            return $manager;
        }

        return $manager->get($key, $default);
    }
}
