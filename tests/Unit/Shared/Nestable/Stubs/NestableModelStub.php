<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Unit\Shared\Nestable\Stubs;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Thinktomorrow\Chief\ManagedModels\Presets\Page;
use Thinktomorrow\Chief\Resource\PageResource;
use Thinktomorrow\Chief\Resource\PageResourceDefault;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\Model\Nestable;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\Page\NestablePageDefault;

class NestableModelStub extends Model implements Page, PageResource, Nestable
{
    use PageResourceDefault;
    use NestablePageDefault;

    protected $guarded = [];
    protected $dynamicKeys = ['title'];
    protected $keyType = 'string';
    public $incrementing = false;

    protected $viewPath = 'test-views::nestable_page';

    public function getCustomMethod(): string
    {
        return 'foobar';
    }

    public static function migrateUp()
    {
        Schema::create((new static)->getTable(), function (Blueprint $table) {
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
        yield $this->parentNodeSelect($model);
    }
}
