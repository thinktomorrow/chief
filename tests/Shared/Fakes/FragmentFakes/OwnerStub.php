<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes;

use Illuminate\Support\Facades\Schema;
use Thinktomorrow\Chief\ManagedModels\Fields\Fields;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Thinktomorrow\Chief\Fragments\FragmentsOwner;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModelDefault;
use Thinktomorrow\Chief\ManagedModels\Assistants\ManagedModelDefaults;

class OwnerStub extends Model implements FragmentsOwner
{
    use ManagedModelDefaults;
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

    public static function managedModelKey(): string
    {
        return 'owner-stub';
    }

    public function fields(): Fields
    {
        // TODO: Implement fields() method.
    }
}
