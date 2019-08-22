<?php

namespace Thinktomorrow\Chief\Tests\Feature\Relations;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Thinktomorrow\Chief\Relations\ActsAsChild;
use Thinktomorrow\Chief\Management\ManagedModel;
use Thinktomorrow\Chief\Relations\ActingAsChild;
use Thinktomorrow\Chief\Concerns\Viewable\Viewable;
use Thinktomorrow\Chief\FlatReferences\FlatReference;
use Thinktomorrow\Chief\Concerns\Viewable\ViewableContract;

class ChildFake extends Model implements ManagedModel, ActsAsChild, ViewableContract
{
    use ActingAsChild, Viewable;

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

    public function renderView(): string
    {
        return '<div>child '.$this->id.' view for parent '.$this->viewParent->id.'</div>';
    }

    public function flatReference(): FlatReference
    {
        return new FlatReference(get_class($this), 1);
    }

    public function flatReferenceLabel(): string
    {
        return (string) $this->name;
    }

    public function flatReferenceGroup(): string
    {
        return 'childfakes';
    }

    public static function managedModelKey(): string
    {
        return 'childfakes';
    }
}
