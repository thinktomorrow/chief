<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\PageSets;

class UpcomingEventSetStoredReference extends StoredPageSetReference
{
//    $label = 'opkomende activieten';

    public static function fetch($limit = 5)
    {
        return Event::upcoming()->limit($limit)->get();
    }
}