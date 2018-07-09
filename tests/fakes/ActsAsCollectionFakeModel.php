<?php

namespace Thinktomorrow\Chief\Tests\Fakes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Thinktomorrow\Chief\Common\Collections\ActingAsCollection;
use Thinktomorrow\Chief\Common\Collections\CollectionDetails;
use Thinktomorrow\Chief\Common\FlatReferences\FlatReference;
use Thinktomorrow\Chief\Common\Collections\ActsAsCollection;

class ActsAsCollectionFakeModel extends Model implements ActsAsCollection
{
    use ActingAsCollection;

    public $table = 'has_collection_fakes';
    public $guarded = [];

    public static function migrateUp()
    {
        Schema::create('has_collection_fakes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('label')->nullable();
            $table->string('collection')->default('has_collection_fakes');
            $table->string('group')->nullable();
            $table->timestamps();
        });
    }

    public function flatReference(): FlatReference
    {
        return new FlatReference(get_class($this), $this->id);
    }

    public function flatReferenceLabel(): string
    {
        return $this->label;
    }

    public function flatReferenceGroup(): string
    {
        return $this->group;
    }

    public function collectionDetails(): CollectionDetails
    {
        return new CollectionDetails(
            'has_collection_fakes',
            get_class($this),
            "fakes",
            "fakes",
            "fakes"
        );
    }
}
