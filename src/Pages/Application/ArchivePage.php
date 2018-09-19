<?php
namespace Thinktomorrow\Chief\Pages\Application;

use Illuminate\Support\Facades\DB;
use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Common\Audit\Audit;
use Thinktomorrow\Chief\Common\Translatable\TranslatableCommand;

class ArchivePage
{
    use TranslatableCommand;
    public function handle($id)
    {
        try {
            DB::beginTransaction();

            if (!$page = Page::find($id)) {
                return;
            }

            $page->archive();

            Audit::activity()
                ->performedOn($page)
                ->log('archived');

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
