<?php

namespace Thinktomorrow\Chief\Tests\Feature\Management\Fakes;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Thinktomorrow\Chief\Concerns\Sortable;
use Thinktomorrow\Chief\Fragments\HasFragments;
use Thinktomorrow\Chief\States\PageState;
use Thinktomorrow\Chief\Relations\ActsAsChild;
use Thinktomorrow\Chief\FlatReferences\FlatReference;
use Thinktomorrow\Chief\Management\ManagedModel;
use Thinktomorrow\AssetLibrary\AssetTrait;
use Thinktomorrow\AssetLibrary\HasAsset;
use Thinktomorrow\Chief\States\State\StatefulContract;
use Thinktomorrow\Chief\States\Publishable\Publishable;
use Thinktomorrow\Chief\Concerns\Translatable\Translatable;
use Thinktomorrow\Chief\DynamicAttributes\HasDynamicAttributes;
use Thinktomorrow\Chief\Concerns\Translatable\TranslatableContract;
use Thinktomorrow\Chief\Relations\ActingAsChild;
use Thinktomorrow\Chief\Relations\ActingAsParent;
use Thinktomorrow\Chief\Relations\ActsAsParent;
use \Astrotomic\Translatable\Translatable as BaseTranslatable;

class ManagedModelFakeFirst extends Model implements ManagedModel, TranslatableContract, HasAsset, ActsAsParent, ActsAsChild, StatefulContract
{
    use HasDynamicAttributes {
        HasDynamicAttributes::fill as hasDynamicAttributesFill;
        HasDynamicAttributes::getAttribute as private hasDynamicAttributesGetAttribute;
        HasDynamicAttributes::setAttribute as hasDynamicAttributesSetAttribute;
    }

    use Translatable;
    use BaseTranslatable {
        BaseTranslatable::getAttribute as private baseTranslatableGetAttribute;
        BaseTranslatable::fill as baseTranslatableFill;
        BaseTranslatable::setAttribute as baseTranslatableSetAttribute;
    }

    use AssetTrait,
        Publishable,
        Sortable,
        ActingAsParent,
        ActingAsChild;

    use HasFragments;

    public $table = 'fake_managed_models';
    public $dynamicKeys = ['dynamic_column'];
    public $translatedAttributes = ['title_trans', 'content_trans', 'slug'];
    public $guarded = [];

    protected $translationModel = ManagedModelFakeTranslation::class;
    protected $translationForeignKey = 'managed_model_fake_id';

    protected static $managedModelKey = 'managed_model_first';

    public static function managedModelKey(): string
    {
        if (isset(static::$managedModelKey)) {
            return static::$managedModelKey;
        }

        throw new \Exception('Missing required static property \'managedModelKey\' on ' . static::class . '.');
    }

    public static function migrateUp()
    {
        Schema::create('fake_managed_models', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable();
            $table->string('custom_column')->nullable();
            $table->string('current_state')->default(PageState::DRAFT);
            $table->tinyInteger('order')->default(0);
            $table->json('values')->nullable(); // dynamic attributes
            $table->dateTime('archived_at')->nullable();
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
        return $this->title ?? '';
    }

    /**
     * Label that identifies the grouping under which this reference should belong.
     * This is a categorization used to group select options and other listings.
     * It also combines similar models together in the view rendering.
     *
     * @return string
     */
    public function flatReferenceGroup(): string
    {
        return 'group';
    }

    public function stateOf($key): string
    {
        return $this->$key;
    }

    public function changeStateOf($key, $state)
    {
        $this->$key = $state;
    }

    public function fill(array $attributes)
    {
        $this->hasDynamicAttributesFill($attributes);

        return $this->baseTranslatableFill($attributes);
    }

    public function getAttribute($key)
    {
        if($value = $this->hasDynamicAttributesGetAttribute($key)) {
            return $value;
        }

        return $this->baseTranslatableGetAttribute($key);
    }

    public function setAttribute($key, $value)
    {
        if ($this->isDynamicKey($key)) {
            return $this->hasDynamicAttributesSetAttribute($key, $value);
        }

        return $this->baseTranslatableSetAttribute($key, $value);
    }
}
