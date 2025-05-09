<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Thinktomorrow\Chief\Fragments\ContextOwner;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModelDefault;

class OwnerStub extends Model implements ContextOwner
{
    use ReferableModelDefault;

    protected $guarded = [];

    public static function migrateUp()
    {
        Schema::create('owner_stubs', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
        });
    }

    public function allowedFragments(): array
    {
        // TODO: Implement allowedFragments() method.
    }

    //    public static function resourceKey(): string
    //    {
    //        return 'owner-stub';
    //    }

    //    public function fields(): Fields
    //    {
    //        // TODO: Implement fields() method.
    //    }
}
