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
            return asset(mix($entry.'/test', $manifestPath));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error($e);

            return $manifestPath.$entry;
        }
    }
}

if(!function_exists('prepareForRedactor')){
    function prepareForRedactor($value) {

        /**
         * For support of columns in the wysiwyg, we'll need to make sure that only the
         * column body is editable. Nice effect of contenteditable is that the hard
         * enter is treated as soft enter inside the column as well as that the
         * tab brings the cursor to the next column in line.
         */
        $value = str_replace('<div class="row', '<div contenteditable="false" class="row', $value);
        $value = str_replace('<div class="column', '<div contenteditable="true" class="column', $value);

        return $value;
    }
}
