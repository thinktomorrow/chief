<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Unit\Shared\Nestable\Stubs;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Thinktomorrow\Chief\ManagedModels\Presets\Page;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\Page\NestablePageDefaults;

class NestableModelStub extends Model implements Page
{
    use NestablePageDefaults;

    protected $guarded = [];
    protected $dynamicKeys = ['title'];
    protected $keyType = 'string';
    public $incrementing = false;

    public function getCustomMethod(): string
    {
        return 'foobar';
    }

    public function getId(): string
    {
        return 'should not be called because Nestable has this method';
    }

    public static function migrateUp()
    {
        Schema::create((new static)->getTable(), function (Blueprint $table) {
            $table->char('id', 36)->primary();
            $table->char('parent_id', 36)->index()->nullable();
            $table->string('title')->nullable();
            $table->json('values')->nullable();
            $table->string('current_state')->default('draft');
            $table->tinyInteger('order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

//    public function isNestable(): bool
//    {
//        return true;
//    }
//
//    /**
//     * Pass the nested node as model to the frontend
//     */
//    public function response(): Response
//    {
//        $this->setNestedNodeAsModelInView();
//
//        return $this->defaultResponse();
//    }
//
//    /**
//     * Allows to pass a predefined parent
//     * for the creation of a new nested model.
//     */
//    public function getInstanceAttributes(Request $request): array
//    {
//        return $this->getNestableInstanceAttributes($request);
//    }

    public function fields($model): iterable
    {
        // Field to select parent model
        yield $this->parentNodeSelect($model);
    }
}
