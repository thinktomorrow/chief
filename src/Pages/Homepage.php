<?php

namespace Thinktomorrow\Chief\Pages;

class Homepage
{
    public static function is(Page $page): bool
    {
        $homepage_id = chiefSetting('homepage');

        return $page->id == $homepage_id;
    }

    // TODO: remove this method - no longer used...
    public static function guess(): Page
    {
        $homepage_id = chiefSetting('homepage');

        // Homepage id is explicitly set
        if ($homepage_id && $page = Page::findPublished($homepage_id)) {
            return $page;
        }

        if ($page = Page::morphable('singles')->published()->first()) {
            return $page;
        }

        if ($page = Page::published()->first()) {
            return $page;
        }

        $message = $homepage_id
                ? 'No homepage could be guessed. There is a homepage id set but make sure it points to an existing, published page. Homepage id is ['.$homepage_id.']'
                : 'No homepage could be guessed. Make sure to provide a published page and set its id in the thinktomorrow.chief-settings.homepage_id config parameter.';

        throw new NotFoundHomepage($message);
    }
}
