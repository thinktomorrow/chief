<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Unit\DynamicAttributes;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Thinktomorrow\Chief\DynamicAttributes\HasDynamicAttributes;

class ModelStub extends Model
{
    use HasDynamicAttributes;

    protected $dynamicAttributes = [
        'title',
    ];

    protected $guarded = [];

    public static function migrateUp()
    {
        Schema::create('model_stubs', function (Blueprint $table) {
            $table->increments('id');
            $table->json('values')->nullable();
            $table->timestamps();
        });
    }
}
