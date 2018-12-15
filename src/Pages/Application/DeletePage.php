<?php
namespace Thinktomorrow\Chief\Pages\Application;

use Illuminate\Support\Facades\DB;
use Thinktomorrow\Chief\Modules\Module;
use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Audit\Audit;

class DeletePage
{
    public function handle($id)
    {
        try {
            DB::beginTransaction();

            $page = Page::withArchived()->findOrFail($id);

            // Can only delete a draft or archived page
            if (!$page->isDraft() && !$page->isArchived()) {
                return;
            }

            // Remove Page specific modules
            Module::where('page_id', $page->id)->delete();

            //Add random string to slug to avoid unique problems with softdeleted pages.
            $page->slug .= 'deleted-'.str_random(8);
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
