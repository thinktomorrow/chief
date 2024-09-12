<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Unit\Shared\Nestable\Stubs;

use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Chief\ManagedModels\Presets\Page;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\Nestable;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\Model\PageDefaultWithNestableUrl;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\NestableDefault;

class NestableModelStub extends Model implements Page, Nestable
{
    use NestableDefault;
    use PageDefaultWithNestableUrl;

    protected $guarded = [];
    protected $dynamicKeys = ['title'];
    protected $keyType = 'string';
    public $incrementing = false;

    protected $viewPath = 'test-views::nestable_page';

    public function getCustomMethod(): string
    {
        return 'foobar';
    }
}
