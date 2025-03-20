<?php

namespace Thinktomorrow\Chief\Sites\Tests\Fixtures;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Thinktomorrow\Chief\Models\Page;
use Thinktomorrow\Chief\Models\PageDefaults;

class LocalizedPageFixture extends Model implements Page
{
    use PageDefaults;

    public $table = 'localized_pages';

    public $dynamicKeys = [
        'title',
    ];

    public static function migrateUp()
    {
        Schema::create('localized_pages', function (Blueprint $table) {
            $table->increments('id');
            $table->json('sites')->nullable();
            $table->json('values')->nullable(); // dynamic attributes
            $table->timestamps();
        });
    }

    public function allowedFragments(): array
    {
        return [];
    }
}
