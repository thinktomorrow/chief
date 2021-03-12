<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels;

use Thinktomorrow\Chief\ManagedModels\Fields\Fields;

interface ManagedModel
{
    public static function managedModelKey(): string;

//    public function fields(): Fields;

    public function saveFields(Fields $fields, array $input, array $files): void;

    public function adminLabel(string $key, $default = null, array $replace = []);
}
