<?php

namespace Thinktomorrow\Chief\Tests\Feature\Management\Fakes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ManagedModelFakeTranslation extends Model
{
    public $table = 'fake_managed_models_translations';
    public $timestamps = false;

    public static function migrateUp()
    {
        Schema::create('fake_managed_models_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('managed_model_fake_id');
            $table->string('locale')->nullable();
            $table->string('title_trans')->nullable();
            $table->string('content_trans')->nullable();
        });
    }
}
