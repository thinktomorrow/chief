<?php

namespace Chief\Tests\Feature\Relations;

use Chief\Common\Relations\ActingAsChild;
use Chief\Common\Relations\ActingAsParent;
use Chief\Common\Relations\ActsAsChild;
use Chief\Common\Relations\ActsAsParent;
use Chief\Common\Relations\Relation;
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
        Schema::create('fake_children', function (Blueprint $table){
            $table->increments('id');
            $table->string('name')->nullable();
        });
    }

    public function presentForParent(ActsAsParent $parent, Relation $relation): string
    {
        return '<div>child '.$this->id.' view for parent '.$parent->id.'</div>';
    }

    public function getRelationId(): string
    {
        return $this->getMorphClass().'@'.$this->id;
    }

    public function getRelationLabel(): string
    {
       return $this->id;
    }

    public function getRelationGroup(): string
    {
        return 'children';
    }
}