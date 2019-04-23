<?php

namespace Thinktomorrow\Chief\Urls;

use Illuminate\Database\Eloquent\Model;

class UrlRecord extends Model
{
    public $table = 'chief_urls';

    public $guarded = [];

    /**
     * Find matching url record for passed slug and locale. The locale parameter will try
     * to match specific given locales first and records without locale as fallback.
     *
     * @param string $slug
     * @param string $locale
     * @return UrlRecord
     * @throws UrlRecordNotFound
     */
    public static function findBySlug(string $slug, string $locale): UrlRecord
    {
        $record = static::where('slug', trim($slug,'/'))
                        ->where(function($query) use($locale){
                            $query->where('locale', $locale)
                                  ->orWhereNull('locale');
                        })

                        // Make sure that the generic non-locale record is fetched after the locale one.
                        ->orderBy('locale', 'DESC')
                        ->first();

        if(!$record){
            throw new UrlRecordNotFound('No url record found by slug ['.$slug.'] for locale ['.$locale.'].');
        }

        return $record;
    }
}