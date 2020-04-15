<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Pages\Application;

use Illuminate\Support\Facades\DB;
use Thinktomorrow\Chief\Modules\Module;
use Thinktomorrow\Chief\Audit\Audit;
use Thinktomorrow\Chief\Urls\UrlRecord;
use Thinktomorrow\Chief\States\PageState;

class DeletePage
{
    public function handle($flatreference)
    {

        try {
            DB::beginTransaction();

            $page = $flatreference->instance();

            // Can only delete a draft or archived page
            if ($page->isPublished()) {
                return;
            }

            // Remove Page specific modules
            Module::where('owner_id', $page->id)->where('owner_type', $page->getMorphClass())->delete();

            // Remove Page specific urls
            UrlRecord::getByModel($page)->each->delete();

            PageState::make($page)->apply('delete');
            $page->save();

            $page->delete();

            Audit::activity()
                ->performedOn($page)
                ->log('deleted');

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
