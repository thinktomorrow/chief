<?php

namespace Thinktomorrow\Chief\Tests\Feature\Management\Fakes;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Thinktomorrow\Chief\States\PageState;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Thinktomorrow\Chief\Management\ManagedModel;
use Thinktomorrow\AssetLibrary\Traits\AssetTrait;
use Thinktomorrow\Chief\States\State\StatefulContract;
use Thinktomorrow\Chief\States\Publishable\Publishable;
use Thinktomorrow\Chief\Concerns\Translatable\Translatable;
use Thinktomorrow\Chief\Concerns\Translatable\TranslatableContract;
use Thinktomorrow\Chief\Relations\ActingAsParent;
use Thinktomorrow\Chief\Relations\ActsAsParent;

class ManagedModelFakeFirst extends Model implements ManagedModel, TranslatableContract, HasMedia, ActsAsParent, StatefulContract
{
    private $current_state = 'draft';

    use Translatable,
        \Astrotomic\Translatable\Translatable,
        AssetTrait,
        Publishable,
        ActingAsParent;

    public $table = 'fake_managed_models';
    public $translatedAttributes = ['title_trans', 'content_trans', 'slug'];
    public $guarded = [];

    protected $translationModel = ManagedModelFakeTranslation::class;
    protected $translationForeignKey = 'managed_model_fake_id';

    protected static $managedModelKey = 'managed_model_first';

    public static function managedModelKey(): string
    {
        if(isset(static::$managedModelKey)){
            return static::$managedModelKey;
        }

        throw new \Exception('Missing required static property \'managedModelKey\' on ' . static::class. '.');
    }

    public static function migrateUp()
    {
        Schema::create('fake_managed_models', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable();
            $table->string('custom_column')->nullable();
            $table->string('current_state')->default(PageState::DRAFT);
            $table->dateTime('archived_at')->nullable();
            $table->timestamps();
        });
    }

    public function state(): string
    {
        return $this->current_state;
    }

    public function changeState($state)
    {
        $this->current_state = $state;
    }
}
