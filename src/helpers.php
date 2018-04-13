<?php

/**
 * Retrieve the logged in admin
 */
if(!function_exists('admin')){
    function admin(){
        return \Illuminate\Support\Facades\Auth::guard('admin')->user();
    }
}


/**
 * Form fields for honeypot protection on form submissions
 */
if(!function_exists('honeypot_fields')){
    function honeypot_fields(){
        return '<div style="display:none;"><input type="text" name="your_name"/><input type="hidden" name="_timer" value="'.time().'" /></div>';
    }
}


/**
 * Retrieve the public asset with a version stamp.
 * This allows for browsercache out of the box
 */
if(!function_exists('cached_asset'))
{
    function cached_asset($filepath, $type = null)
    {
        $manifestPath = $type == 'back' ? '/assets/back' : '/assets';

        // Manifest expects each entry to start with a leading slash - we make sure to deduplicate the manifest path.
        $entry = str_replace($manifestPath,'', '/'.ltrim($filepath,'/') );

        try{
            // Paths should be given relative to the manifestpath so make sure to remove the basepath
            return asset( mix($entry, $manifestPath) );
        }
        catch(\Exception $e)
        {
            app('bugsnag')->notifyException($e);

            return $manifestPath.$entry;
        }

    }
}

if(!function_exists('trans_to'))
{
    /**
     * Shortcut to trans when you want to force a specific locale
     *
     * @param null $id
     * @param array $parameters
     * @param null $locale
     * @return string
     */
    function trans_to($id = null, $parameters = [], $locale = null)
    {
        return trans($id, $parameters, 'messages', $locale);
    }
}