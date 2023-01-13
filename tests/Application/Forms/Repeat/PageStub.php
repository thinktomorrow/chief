<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Application\Forms\Repeat;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Thinktomorrow\Chief\Forms\Fields\Repeat;
use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Forms\Form;
use Thinktomorrow\Chief\Forms\Layouts\Grid;
use Thinktomorrow\Chief\Resource\PageResource;
use Thinktomorrow\Chief\Resource\PageResourceDefault;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModel;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModelDefault;

class PageStub extends Model implements PageResource, ReferableModel
{
    use PageResourceDefault;
    use ReferableModelDefault;

    public $casts = [
        'repeat_values' => 'array',
    ];

    public $timestamps = false;

    public static function modelClassName(): string
    {
        return static::class;
    }

    public function fields($model): iterable
    {
        yield Form::make('repeat_form')->items([
            Repeat::make('repeat_values')->items([
                Text::make('first'),
                Text::make('second'),
                Repeat::make('nested')->items([
                    Text::make('nested-first'),
                    Text::make('nested-second'),
                ]),
                Grid::make('grid')->items([
                    Text::make('grid-first'),
                    Text::make('grid-second'),
                ]),
            ]),
        ]);
    }

    public static function migrateUp()
    {
        Schema::create('page_stubs', function (Blueprint $table) {
            $table->increments('id');
            $table->json('repeat_values')->nullable();
        });
    }
}
