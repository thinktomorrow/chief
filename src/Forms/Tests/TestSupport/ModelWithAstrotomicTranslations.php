<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Tests\TestSupport;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Thinktomorrow\Chief\Forms\App\Queries\Fields;
use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Resource\PageResource;
use Thinktomorrow\Chief\Resource\PageResourceDefault;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModel;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModelDefault;

class ModelWithAstrotomicTranslations extends Model implements PageResource, ReferableModel
{
    use \Astrotomic\Translatable\Translatable;
    use PageResourceDefault;
    use ReferableModelDefault;

    public $table = 'astrotomic_models';

    public $guarded = [];

    private $translatedAttributes = [
        'title_trans',
    ];

    public static function modelClassName(): string
    {
        return static::class;
    }

    public function fields($model): Fields
    {
        return Fields::make([
            Text::make('title_trans')->locales(),
        ]);
    }

    public static function migrateUp()
    {
        Schema::create('astrotomic_models', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('astrotomic_model_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('model_with_astrotomic_translations_id')->unsigned();
            $table->string('locale');
            $table->string('title_trans')->nullable();
            $table->timestamps();

            $table->foreign('model_with_astrotomic_translations_id')->references('id')->on('astrotomic_models')->onDelete('cascade');
        });
    }
}
