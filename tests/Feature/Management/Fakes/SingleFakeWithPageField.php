<?php

declare(strict_types = 1);

namespace Thinktomorrow\Chief\Tests\Feature\Management\Fakes;

use Thinktomorrow\Chief\Pages\Page;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Thinktomorrow\Chief\FlatReferences\HasFlatReferences;

class SingleFakeWithPageField extends Page
{
    use HasFlatReferences;

    public static function migrateUp()
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->text('buttonlink')->nullable()->default(NULL);
        });
    }

    public function getButtonlinkAttribute($value)
    {
        return $this->flatReferenceToModel($value)->url(app()->getLocale());
    }
}
