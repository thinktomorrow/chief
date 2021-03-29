<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels;

use Thinktomorrow\Chief\Admin\AdminConfig;
use Thinktomorrow\Chief\ManagedModels\Fields\Fields;

interface ManagedModel
{
    public static function managedModelKey(): string;

    public function adminConfig(): AdminConfig;

    public function fields(): iterable;

    public function saveFields(Fields $fields, array $input, array $files): void;
}
