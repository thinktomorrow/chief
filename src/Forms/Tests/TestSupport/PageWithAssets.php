<?php

namespace Thinktomorrow\Chief\Forms\Tests\TestSupport;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Thinktomorrow\Chief\Models\Page;
use Thinktomorrow\Chief\Models\PageDefaults;
use Thinktomorrow\Chief\Resource\PageResource;
use Thinktomorrow\Chief\Resource\PageResourceDefault;
use Thinktomorrow\Chief\Tests\Shared\Fakes\WithCustomFieldDefinitions;

class PageWithAssets extends Model implements Page, PageResource
{
    use PageDefaults;
    use PageResourceDefault;
    use WithCustomFieldDefinitions;

    public $table = 'page_with_assets';

    public $guarded = [];

    public static function migrateUp()
    {
        Schema::create('page_with_assets', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
        });
    }
}
