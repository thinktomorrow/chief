<?php

namespace Thinktomorrow\Chief\Tests\Feature\Relations;

use Thinktomorrow\Chief\Concerns\Viewable\Viewable;
use Thinktomorrow\Chief\Concerns\Viewable\ViewableContract;
use Thinktomorrow\Chief\FlatReferences\FlatReference;
use Thinktomorrow\Chief\Relations\ActingAsChild;
use Thinktomorrow\Chief\Relations\ActsAsChild;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChildFake extends Model implements ActsAsChild, ViewableContract
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
}
