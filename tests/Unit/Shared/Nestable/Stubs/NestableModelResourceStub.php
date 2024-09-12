<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Unit\Shared\Nestable\Stubs;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Thinktomorrow\Chief\ManagedModels\Presets\Page;
use Thinktomorrow\Chief\Resource\PageResource;
use Thinktomorrow\Chief\Resource\PageResourceDefault;
use Thinktomorrow\Chief\Resource\TreeResource;
use Thinktomorrow\Chief\Resource\TreeResourceDefault;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\Actions\NestableFormPresets;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\Nestable;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\Model\PageDefaultWithNestableUrl;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\NestableDefault;

class NestableModelResourceStub implements PageResource, TreeResource
{
    use PageResourceDefault;
    use TreeResourceDefault;

    public static function migrateUp()
    {
        Schema::create((new NestableModelStub())->getTable(), function (Blueprint $table) {
            $table->char('id', 36)->primary();
            $table->char('parent_id', 36)->index()->nullable();
            $table->json('values')->nullable();
            $table->string('current_state')->default('draft');
            $table->tinyInteger('order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function fields($model): iterable
    {
        // Field to select parent model
        yield NestableFormPresets::parentSelect($model);
    }

    public static function modelClassName(): string
    {
        return NestableModelStub::class;
    }
}
