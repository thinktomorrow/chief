<?php

namespace Chief\Tests\Feature\Relations;

use Chief\Common\Relations\ActingAsParent;
use Chief\Common\Relations\ActsAsChild;
use Chief\Common\Relations\ActsAsParent;
use Chief\Common\Relations\Relation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ParentFake extends Model implements ActsAsParent
{
    use ActingAsParent;

    public $table = 'fake_parents';
    public $timestamps = false;
    public $guarded = [];

    public static function migrate()
    {
        Schema::create('fake_parents', function (Blueprint $table){
            $table->increments('id');
            $table->string('name')->nullable();
        });
    }

    public function presentForChild(ActsAsChild $child, Relation $relation): string
    {
        return '<div>parent '.$this->id.' view for child '.$child->id.'</div>';
    }

    public function getCompositeKey(): string
    {
        return $this->getMorphClass().'@'.$this->id;
    }
}