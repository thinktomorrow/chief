<?php

namespace Thinktomorrow\Chief\Plugins\AdminToast;

use Thinktomorrow\Chief\ManagedModels\States\Publishable\PreviewMode;

class AdminToastHTML
{
    /**
     * Load this html inside your head tags. The metatags contain a reference of the current request
     * that the admin toast controller will use to determine edit rights and url. If you use some
     * SSR principle like turbolinks, htmlx or barba.js, place this inside the updatable body.
     */
    public function metatags(): string
    {
        $requestPath = request()->path();
        $locale = app()->getLocale();
        $previewMode = PreviewMode::fromRequest()->check();

        return <<<HTML
<meta id="jsChiefToastPath" content="{$requestPath}">
<meta id="jsChiefToastLocale" content="{$locale}">
<meta id="jsChiefToastPreviewMode" content="{$previewMode}">
HTML;

    }

    /**
     * This runs the admin toast script and fetches the toast element with correct links and options.
     * By default this is done on each request when the DOM content is loaded but you can
     * call the loadAdminToast() script in your own event callbacks if needed.
     */
    public function scripts(string $toastElementSelector = '#jsChiefToast'): string
    {
        $toastUrl = route('chief.toast.get');

        return <<<HTML
<div id="jsChiefToast"></div>

<script>

    function loadAdminToast(toastElementSelector = '{$toastElementSelector}') {
        try {
            const toast = document.querySelector(toastElementSelector);
            const toastPath = document.getElementById('jsChiefToastPath').content;
            const toastLocale = document.getElementById('jsChiefToastLocale').content;
            const toastPreviewMode = document.getElementById('jsChiefToastPreviewMode').content;

             fetch("{$toastUrl}?path="+toastPath+"&locale="+toastLocale+"&preview_mode=" + toastPreviewMode)
                .then((response) => response.json())
                .then((data) => {
                    if(data.data) {
                        toast.innerHTML = data.data;
                        listenForClose();
                    }
                })
                .catch((error) => {
                    console.error(error);
                });

            function listenForClose() {
                const toastClose = toast.querySelector('[data-admin-toast-close]');

                toastClose.addEventListener('click', function() {
                    toast.style.display = "none";
                });
            }
        } catch(error) {
            console.log(error);
        }
    }

    window.addEventListener('DOMContentLoaded', () => {
        loadAdminToast();
    })
</script>
HTML;
    }

    /** Blade expression */
    public static function chiefAdminToastMetatags($expression)
    {
        return '{!! app(\Thinktomorrow\Chief\Plugins\AdminToast\AdminToastHTML::class)->metatags('.$expression.') !!}';
    }

    /** Blade expression */
    public static function chiefAdminToastScripts($expression)
    {
        return '{!! app(\Thinktomorrow\Chief\Plugins\AdminToast\AdminToastHTML::class)->scripts('.$expression.') !!}';
    }
}
