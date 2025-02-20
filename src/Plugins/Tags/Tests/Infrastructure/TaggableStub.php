<?php

namespace Thinktomorrow\Chief\Plugins\Tags\Tests\Infrastructure;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Thinktomorrow\Chief\Plugins\Tags\App\Taggable\Taggable;
use Thinktomorrow\Chief\Plugins\Tags\Infrastructure\Models\TaggableDefaults;

class TaggableStub extends Model implements Taggable
{
    use TaggableDefaults;

    public $table = 'taggable_stubs';

    public $guarded = [];

    public static function migrateUp()
    {
        Schema::create('taggable_stubs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable();
            $table->json('values')->nullable(); // dynamic attributes
            $table->timestamps();
        });
    }
}
