<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\TableNew\Filters;

use Closure;
use Illuminate\Http\Request;

interface Filter
{
    public function queryKey(): string;

    public function query(): Closure;

    public function render(): string;

    public function getValue();
}
