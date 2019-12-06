<?php

namespace Thinktomorrow\Chief\Tests\Feature\Management\Fakes;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Thinktomorrow\Chief\States\PageState;
use Thinktomorrow\Chief\Management\ManagedModel;
use Thinktomorrow\Chief\States\Publishable\Publishable;
use Thinktomorrow\AssetLibrary\AssetTrait;
use Thinktomorrow\AssetLibrary\HasAsset;
use Thinktomorrow\Chief\Concerns\Translatable\Translatable;
use Thinktomorrow\Chief\Concerns\Translatable\TranslatableContract;

class ManagedModelFakeSecond extends Model implements  ManagedModel, TranslatableContract, HasAsset
{
    use Translatable, \Astrotomic\Translatable\Translatable, AssetTrait, Publishable;

    public $table = 'fake_managed_models';
    public $translatedAttributes = ['title_trans', 'content_trans', 'slug'];
    public $guarded = [];

    protected static $managedModelKey = 'managed_model_second';

    public static function managedModelKey(): string
    {
        if(isset(static::$managedModelKey)){
            return static::$managedModelKey;
        }

        throw new \Exception('Missing required static property \'managedModelKey\' on ' . static::class. '.');
    }

    public static function migrateUp()
    {
        Schema::create('fake_managed_models', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable();
            $table->string('custom_column')->nullable();
            $table->string('current_state')->default(PageState::DRAFT);
            $table->dateTime('archived_at')->nullable();
            $table->timestamps();
        });
    }
}
