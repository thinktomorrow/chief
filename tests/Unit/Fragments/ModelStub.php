<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Unit\Fragments;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Thinktomorrow\Chief\Fragments\HasFragments;

class ModelStub extends Model
{
    use HasFragments;

    protected $guarded = [];

    public static function migrateUp()
    {
        Schema::create('model_stubs', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
        });
    }
}
