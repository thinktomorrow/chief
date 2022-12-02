<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Filters;

use Closure;

interface Filter
{
    public function applicable(array $parameterBag): bool;

    public function queryKey(): string;

    public function query(): Closure;

    public function render(array $parameterBag): string;
}
