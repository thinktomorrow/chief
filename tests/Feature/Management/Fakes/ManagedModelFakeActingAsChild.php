<?php

namespace Thinktomorrow\Chief\Tests\Feature\Management\Fakes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Thinktomorrow\Chief\FlatReferences\FlatReference;
use Thinktomorrow\Chief\Relations\ActingAsChild;
use Thinktomorrow\Chief\Relations\ActsAsChild;
use Thinktomorrow\Chief\Relations\ActsAsParent;
use Thinktomorrow\Chief\Relations\PresentForParent;

class ManagedModelFakeActingAsChild extends Model implements ActsAsChild, PresentForParent
{
    use ActingAsChild;

    public $table = 'fake_managed_models';
    public $guarded = [];

    public static function migrateUp()
    {
        Schema::create('fake_managed_models', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Composite key consisting of the type of class combined with the
     * model id. Both are joined with an @ symbol. This is used as
     * identifier of the instance mostly in selections.
     */
    public function flatReference(): FlatReference
    {
        return new FlatReference(static::class, $this->id);
    }

    /**
     * Label that identifies the flat reference with a human readable string.
     * This is mostly used in the interface of the admin panel.
     *
     * @return string
     */
    public function flatReferenceLabel(): string
    {
        return $this->title;
    }

    /**
     * Label that identifies the grouping under which this reference should belong.
     * This is a categorization used to group select options and other listings
     *
     * @return string
     */
    public function flatReferenceGroup(): string
    {
        return 'customs';
    }

    public function presentForParent(ActsAsParent $parent): string
    {
        return 'ManagedModelFakeActingAsChild presentation as child';
    }

    public function viewKey(): string
    {
        return 'managed-model-fakes';
    }
}
