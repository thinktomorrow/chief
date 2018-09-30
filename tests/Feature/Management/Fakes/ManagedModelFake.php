<?php

namespace Thinktomorrow\Chief\Tests\Feature\Management\Fakes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;
use Thinktomorrow\AssetLibrary\Traits\AssetTrait;
use Thinktomorrow\Chief\Common\Translatable\Translatable;
use Thinktomorrow\Chief\Common\Translatable\TranslatableContract;

class ManagedModelFake extends Model implements TranslatableContract, HasMedia, \Thinktomorrow\Chief\Management\ManagedModel
{
    use Translatable, \Dimsav\Translatable\Translatable, AssetTrait;

    public $table = 'fake_managed_models';
    public $translatedAttributes = ['title_trans', 'content_trans'];
    public $guarded = [];

    public static function migrateUp()
    {
        Schema::create('fake_managed_models', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable();
            $table->string('custom_column')->nullable();
            $table->timestamps();
        });
    }

    public static function managerKey(): string
    {
        return 'fakes';
    }
}