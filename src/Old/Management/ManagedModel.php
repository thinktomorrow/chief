<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Old\Management;

interface ManagedModel
{
    public static function managedModelKey(): string;
}
