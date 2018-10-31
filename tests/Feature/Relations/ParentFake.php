<?php

namespace Thinktomorrow\Chief\Tests\Feature\Relations;

use Thinktomorrow\Chief\Common\Morphable\Morphable;
use Thinktomorrow\Chief\FlatReferences\FlatReference;
use Thinktomorrow\Chief\Relations\ActingAsParent;
use Thinktomorrow\Chief\Relations\ActsAsParent;
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
        Schema::create('fake_parents', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
        });
    }

    public function viewKey(): string
    {
        return 'parentfake';
    }
}
