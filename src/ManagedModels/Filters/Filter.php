<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Filters;

use Thinktomorrow\Chief\Shared\Concerns\ProvidesQuery;

interface Filter extends ProvidesQuery
{
    public function applicable(array $parameterBag): bool;

    public function queryKey(): string;

    public function render(array $parameterBag): string;
}
