<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Unit\ManagedModels\Astrotomic;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Thinktomorrow\Chief\ManagedModels\ManagedModel;
use Thinktomorrow\Chief\ManagedModels\Fields\Fields;
use Thinktomorrow\Chief\ManagedModels\Fields\Types\InputField;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModelDefault;
use Thinktomorrow\Chief\ManagedModels\Assistants\ManagedModelDefaults;

class QuoteWithAstrotomicTranslations extends Model implements ManagedModel
{
    use ManagedModelDefaults;
    use ReferableModelDefault;
    use \Astrotomic\Translatable\Translatable;

    public $table = 'quotes';
    public $guarded = [];
    private $translatedAttributes = [
        'title_trans',
    ];

    public function fields(): Fields
    {
        return new Fields([
            InputField::make('title_trans')->locales(['nl', 'en']),
        ]);
    }

    public static function migrateUp()
    {
        Schema::create('quotes', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('quote_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('quote_with_astrotomic_translations_id')->unsigned();
            $table->string('locale');
            $table->string('title_trans')->nullable();
            $table->timestamps();

            $table->foreign('quote_with_astrotomic_translations_id')->references('id')->on('quotes')->onDelete('cascade');
        });
    }
}
