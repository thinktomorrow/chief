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

            $page = Page::ignoreCollection()->withArchived()->findOrFail($id);

            if ($page->isDraft() || $page->isArchived()) {
                $page->delete();

                Audit::activity()
                    ->performedOn($page)
                    ->log('deleted');
            }
            
            if ($page->isPublished()) {
                $page->archive();

                Audit::activity()
                    ->performedOn($page)
                    ->log('archived');
            }

            DB::commit();

            return $page;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
