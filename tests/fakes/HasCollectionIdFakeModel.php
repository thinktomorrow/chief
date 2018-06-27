<?php

namespace Thinktomorrow\Chief\Tests\Fakes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Thinktomorrow\Chief\Common\Collections\CollectionId;
use Thinktomorrow\Chief\Common\Collections\HasCollectionId;

class HasCollectionIdFakeModel extends Model implements HasCollectionId
{
    public $table = 'has_collection_fakes';
    public $guarded = [];

    public static function migrateUp()
    {
        Schema::create('has_collection_fakes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('label')->nullable();
            $table->string('group')->nullable();
            $table->timestamps();
        });
    }

    public function getCollectionId(): CollectionId
    {
        return new CollectionId(get_class($this), $this->id);
    }

    public function getCollectionLabel(): string
    {
        return $this->label;
    }

    public function getCollectionGroup(): string
    {
        return $this->group;
    }
}