<?php

namespace Thinktomorrow\Chief\Tests\Feature\Relations;

use Thinktomorrow\Chief\Common\FlatReferences\ActsAsFlatReference;
use Thinktomorrow\Chief\Common\FlatReferences\Types\SimpleFlatReference;
use Thinktomorrow\Chief\Common\Relations\ActingAsChild;
use Thinktomorrow\Chief\Common\Relations\ActsAsChild;
use Thinktomorrow\Chief\Common\Relations\ActsAsParent;
use Thinktomorrow\Chief\Common\Relations\Relation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChildFake extends Model implements ActsAsChild
{
    use ActingAsChild;

    public $table = 'fake_children';
    public $timestamps = false;
    public $guarded = [];

    public static function migrate()
    {
        Schema::create('fake_children', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
        });
    }

    public function presentForParent(ActsAsParent $parent, Relation $relation): string
    {
        return '<div>child '.$this->id.' view for parent '.$parent->id.'</div>';
    }

    /**
     * Composite key consisting of the type of class combined with the
     * model id. Both are joined with an @ symbol. This is used as
     * identifier of the relation mostly as form values.
     */
    public function flatReference(): ActsAsFlatReference
    {
        return new SimpleFlatReference(get_class($this), 1);
    }

    public function flatReferenceLabel(): string
    {
        return (string) $this->name;
    }

    public function flatReferenceGroup(): string
    {
        return (string) $this->name;
    }
}
