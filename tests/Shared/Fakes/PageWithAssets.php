<?php

namespace Thinktomorrow\Chief\Tests\Shared\Fakes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Thinktomorrow\AssetLibrary\InteractsWithAssets;
use Thinktomorrow\Chief\Models\Page;
use Thinktomorrow\Chief\Models\PageDefaults;
use Thinktomorrow\Chief\Resource\PageResource;
use Thinktomorrow\Chief\Resource\PageResourceDefault;

class PageWithAssets extends Model implements Page, PageResource
{
    use InteractsWithAssets;
    use PageDefaults;
    use PageResourceDefault;

    private static ?\Closure $fieldsDefinition = null;

    public $table = 'page_with_assets';

    public $guarded = [];

    public static function migrateUp()
    {
        Schema::create('page_with_assets', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
        });
    }

    public function fields($model): iterable
    {
        if (! static::$fieldsDefinition) {
            return [];
        }

        return call_user_func_array(static::$fieldsDefinition, [$model]);
    }

    public static function setFieldsDefinition(callable $fields): void
    {
        static::$fieldsDefinition = $fields;
    }
}
