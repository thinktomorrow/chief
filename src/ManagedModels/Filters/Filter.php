<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Filters;

use Closure;
use Illuminate\Http\Request;

interface Filter
{
    public function applicable(Request $request): bool;

    public function queryKey(): string;

    public function query(): Closure;

    public function render(): string;
}
