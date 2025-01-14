<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Table\Tests\Fixtures;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\Nestable;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\NestableDefault;
use Thinktomorrow\DynamicAttributes\HasDynamicAttributes;

class TreeModelFixture extends Model implements Nestable
{
    use HasDynamicAttributes;
    use NestableDefault;

    public $table = 'chief_table_tree_models_fixtures';
    public $guarded = [];
    public $timestamps = false;

    public $dynamicKeys = [
        'dynamic_title',
    ];

    public static function migrateUp()
    {
        Schema::create('chief_table_tree_models_fixtures', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('title')->nullable();
            $table->json('values')->nullable(); // dynamic attributes
        });
    }

    public function categories()
    {
        return collect(['first category', 'second category']);
    }
}
