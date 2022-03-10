<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels;

use Illuminate\Contracts\View\View;
use Thinktomorrow\Chief\Admin\AdminConfig;
use Thinktomorrow\Chief\Forms\Fields\Field;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModel;

interface ManagedModel extends ReferableModel
{
    public static function managedModelKey(): string;

    public function adminConfig(): AdminConfig;

    public function adminView(): View;

    public function fields(): iterable;

    public function field(string $key): Field;
}
