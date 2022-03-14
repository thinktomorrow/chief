<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Application\Forms\Repeat;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Forms\Layouts\Grid;
use Thinktomorrow\Chief\Forms\Fields\Repeat;
use Thinktomorrow\Chief\ManagedModels\ManagedModel;
use Thinktomorrow\Chief\ManagedModels\States\PageState;
use Thinktomorrow\Chief\ManagedModels\Assistants\ManagedModelDefaults;

class PageStub extends Model implements ManagedModel
{
    use ManagedModelDefaults;

    public $timestamps = false;

    public function fields(): iterable
    {
        yield Repeat::make('repeat_values')->items([
            Text::make('first'),
            Repeat::make('nested')->items([
                Text::make('nested-first'),
                Text::make('nested-second'),
            ]),
            Grid::make('grid')->items([
                Text::make('grid-first'),
                Text::make('grid-second'),
            ]),
            Text::make('second'),
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
