<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Table\Tests\Fixtures;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Thinktomorrow\Chief\Plugins\Tags\App\Taggable\Taggable;
use Thinktomorrow\Chief\Plugins\Tags\Infrastructure\Models\TaggableDefaults;

class TaggedModelFixture extends Model implements Taggable
{
    use TaggableDefaults;

    public $table = 'chief_table_tagged_model_fixtures';

    public $guarded = [];

    public $timestamps = false;

    public static function migrateUp()
    {
        Schema::create('chief_table_tagged_model_fixtures', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable();
            $table->json('values')->nullable(); // dynamic attributes
        });
    }
}
