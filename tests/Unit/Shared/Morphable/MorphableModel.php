<?php

namespace Thinktomorrow\Chief\Tests\Unit\Shared\Morphable;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Thinktomorrow\Chief\Shared\Concerns\Morphable\Morphable;
use Thinktomorrow\Chief\Shared\Concerns\Morphable\MorphableContract;

class MorphableModel extends Model implements MorphableContract
{
    use Morphable;

    public $table = 'morphables';
    public $guarded = [];

    public static function migrateUp()
    {
        Schema::create('morphables', function (Blueprint $table) {
            $table->increments('id');
            $table->string('morph_key');
            $table->integer('parent_id')->nullable();
            $table->timestamps();
        });
    }
}
