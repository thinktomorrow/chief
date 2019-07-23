<?php

namespace Thinktomorrow\Chief\Tests\Feature\Management\Fakes;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Thinktomorrow\Chief\Management\ManagedModel;

class ManagedModelFakeTranslation extends Model implements ManagedModel
{
    public $table = 'fake_managed_models_translations';
    public $timestamps = false;
    public $guarded = [];

    protected static $managedModelKey = 'managed_model_trans';


    public static function managedModelKey(): string
    {
        if(isset(static::$managedModelKey)){
            return static::$managedModelKey;
        }

        throw new \Exception('Missing required static property \'managedModelKey\' on ' . static::class. '.');
    }

    public static function migrateUp()
    {
        Schema::create('fake_managed_models_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('managed_model_fake_id');
            $table->string('locale')->nullable();
            $table->string('title_trans')->nullable();
            $table->string('content_trans')->nullable();
            $table->string('slug')->nullable();
        });
    }
}
