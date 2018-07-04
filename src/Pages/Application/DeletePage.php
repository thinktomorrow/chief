<?php
namespace Thinktomorrow\Chief\Pages\Application;

use Illuminate\Support\Facades\DB;
use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Common\Translatable\TranslatableCommand;

class DeletePage
{
    use TranslatableCommand;
    public function handle($id)
    {
        try {
            DB::beginTransaction();

            $page = Page::withArchived()->findOrFail($id);

            if ($page->isDraft() || $page->isArchived()) {
                $page->delete();
            }
            
            if ($page->isPublished()) {
                $page->archive();
            }

            DB::commit();

            return $page;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
