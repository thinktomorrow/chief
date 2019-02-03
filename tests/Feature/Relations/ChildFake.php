<?php

namespace Thinktomorrow\Chief\Tests\Feature\Relations;

use Thinktomorrow\Chief\FlatReferences\FlatReference;
use Thinktomorrow\Chief\Relations\ActingAsChild;
use Thinktomorrow\Chief\Relations\ActsAsChild;
use Thinktomorrow\Chief\Relations\ActsAsParent;
use Thinktomorrow\Chief\Relations\PresentForParent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChildFake extends Model implements ActsAsChild, PresentForParent
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

    public function presentForParent(ActsAsParent $parent): string
    {
        return '<div>child '.$this->id.' view for parent '.$parent->id.'</div>';
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
        return (string) $this->name;
    }

    public function viewKey(): string
    {
        return 'childfakes';
    }
}
