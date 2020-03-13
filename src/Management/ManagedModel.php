<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Management;

interface ManagedModel
{
    public static function managedModelKey(): string;
}
