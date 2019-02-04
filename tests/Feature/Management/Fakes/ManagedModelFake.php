<?php

namespace Thinktomorrow\Chief\Tests\Feature\Management\Fakes;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Thinktomorrow\AssetLibrary\Traits\AssetTrait;
use Thinktomorrow\Chief\Concerns\Publishable\Publishable;
use Thinktomorrow\Chief\Concerns\Translatable\Translatable;
use Thinktomorrow\Chief\Concerns\Translatable\TranslatableContract;

class ManagedModelFake extends Model implements TranslatableContract, HasMedia
{
    use Translatable, \Dimsav\Translatable\Translatable, AssetTrait, Publishable;

    public $table = 'fake_managed_models';
    public $translatedAttributes = ['title_trans', 'content_trans', 'slug'];
    public $guarded = [];

    public static function migrateUp()
    {
        Schema::create('fake_managed_models', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable();
            $table->string('custom_column')->nullable();
            $table->boolean('published')->default(0);
            $table->timestamps();
        });
    }
}
