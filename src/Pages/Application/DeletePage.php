<?php
namespace Thinktomorrow\Chief\Pages\Application;

use Illuminate\Support\Facades\DB;
use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Common\Audit\Audit;
use Thinktomorrow\Chief\Common\Translatable\TranslatableCommand;

class DeletePage
{
    use TranslatableCommand;
    public function handle($id)
    {
        try {
            DB::beginTransaction();

            $page = Page::withArchived()->findOrFail($id);

            // Can only delete a draft of archived page
            if (!$page->isDraft() && !$page->isArchived()) {
                return;
            }

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
