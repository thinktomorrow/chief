<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Thinktomorrow\Chief\Fragments\Assistants\FragmentableDefaults;
use Thinktomorrow\Chief\Fragments\Assistants\OwningFragments;
use Thinktomorrow\Chief\Fragments\Fragmentable;
use Thinktomorrow\Chief\Fragments\FragmentsOwner;
use Thinktomorrow\Chief\ManagedModels\Assistants\ManagedModelDefaults;
use Thinktomorrow\Chief\ManagedModels\Fields\Fields;
use Thinktomorrow\Chief\ManagedModels\Fields\Types\InputField;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModelDefault;

class FragmentableStub extends Model implements Fragmentable, FragmentsOwner
{
    use ManagedModelDefaults;
    use ReferableModelDefault;
    use FragmentableDefaults;
    use OwningFragments;

    protected $guarded = [];

    public static function migrateUp()
    {
        Schema::create('fragmentable_stubs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable();
            $table->timestamps();
        });
    }

    public function renderAdminFragment($owner, $loop, $fragments)
    {
        // TODO: Implement renderAdminFragment() method.
    }

    public function renderFragment($owner, $loop, $fragments, $viewData): string
    {
        return 'fragment-stub-'.$this->id.' ';
    }

    public function allowedFragments(): array
    {
        // TODO: Implement allowedFragments() method.
    }

    public static function managedModelKey(): string
    {
        return 'fragment-stub';
    }

    public function fields(): Fields
    {
        return new Fields([
            InputField::make('title')->tag('create'),
        ]);
    }
}
