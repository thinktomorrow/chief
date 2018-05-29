<?php

namespace Thinktomorrow\Chief\Models\Notes;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cookie;

class NoteReminder
{
    public static function watch($id, Carbon $updated, $locale)
    {
        $cookie = Cookie::get('optiphar-note');
        $cookie[$id] = ['locale' => $locale, 'updated_at' => $updated->toDateTimeString()];

        Cookie::queue(Cookie::forever('optiphar-note', $cookie));
    }

    public static function hasWatched(Note $note, $locale)
    {
        $cookie = Cookie::get('optiphar-note');

        if (! isset($cookie[$note->id])) {
            return false;
        }

        return ! self::shouldWatchAgain($note, $cookie[$note->id], $locale);
    }

    /**
     * If visitor has already seen the note but since then
     * note has changed or he switches locale
     *
     * @param Note $note
     * @param array $cookieData
     * @param $locale
     *
     * @return bool
     * @internal param $noteId
     * @internal param $updated_at
     */
    private static function shouldWatchAgain(Note $note, array $cookieData, $locale)
    {
        if (!isset($cookieData['locale']) || !isset($cookieData['updated_at'])) {
            throw new \LogicException('Cookie contains corrupted data. Locale or updated value missing for cookie ['.$note->id.'].');
        }

        if ($cookieData['locale'] != $locale) {
            return true;
        }

        if ($cookieData['updated_at'] != $note->updated_at) {
            return true;
        }
    }
}
