<?php

namespace Thinktomorrow\Chief\Tests\Feature\Management\Fakes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Thinktomorrow\Chief\Concerns\Viewable\ViewableContract;
use Thinktomorrow\Chief\FlatReferences\FlatReference;
use Thinktomorrow\Chief\Relations\ActingAsChild;
use Thinktomorrow\Chief\Relations\ActsAsChild;
use Thinktomorrow\Chief\Relations\ActsAsParent;

class ManagedModelFakeActingAsChild extends Model implements ActsAsChild, ViewableContract
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

    public function viewKey(): string
    {
        return 'managed-model-fakes';
    }

    public function setKey(): string
    {
        return $this->viewKey();
    }

    /**
     * This is the full path reference for this model's view file. This is the relative to your view folder
     * e.g. key 'articles.show'. A sensible default is set and determined based on the viewKey value.
     *
     * @return string
     */
    public function viewPath(): string
    {
        return '';
    }

    /**
     * Renders the view for this model. It makes use of the viewPath to get the proper
     * view and is responsible for passed the expected view data parameters.
     *
     * @return string
     */
    public function renderView(): string
    {
        return 'ManagedModelFakeActingAsChild presentation as child';
    }

    /**
     * Sets the models parent for the current view path and rendering.
     * In case the model is rendered within a related parent view, this affects
     * the way the view is determined as well as the passed data parameters.
     *
     * @param ActsAsParent $parent
     * @return mixed
     */
    public function setViewParent(ActsAsParent $parent): ViewableContract
    {
        return $this;
    }
}
