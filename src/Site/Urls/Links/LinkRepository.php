<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Site\Urls\Links;

use Illuminate\Support\Facades\DB;
use Thinktomorrow\Chief\Site\Urls\UrlStatus;

class LinkRepository
{
    public function __construct()
    {
    }

    /**
     * @return Link[]
     */
    public function getOnlineLinks(string $locale): array
    {
        $records = DB::table('chief_urls')
            ->whereNull('redirect_id')
            ->where('locale', $locale)
            ->where('status', UrlStatus::online->value)
            ->select('model_type', 'model_id', 'status', 'locale', 'slug', 'internal_label')
            ->groupBy('model_type', 'model_id')
            ->get()
        ;

        return $records->map(function (\stdClass $record) {
            return Link::fromMappedData($record);
        })->all();
    }

    /**
     * @return Link[]
     */
    public function getOnlineLinksFor(string $modelType, string $modelId): array
    {
        $records = DB::table('chief_urls')
            ->whereNull('redirect_id')
            ->where('model_type', $modelType)
            ->where('model_id', $modelId)
            ->where('status', UrlStatus::online->value)
            ->select('model_type', 'model_id', 'status', 'locale', 'slug', 'internal_label')
            ->get()
        ;

        return $records->map(function (\stdClass $record) {
            return Link::fromMappedData($record);
        })->all();
    }
}
