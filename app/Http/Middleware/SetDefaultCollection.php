<?php

namespace Thinktomorrow\Chief\App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\URL;
use Thinktomorrow\Chief\Pages\Page;

class SetDefaultCollection
{
    public function handle($request, Closure $next)
    {
        URL::defaults(['collection' => Page::collectionKey()]);

        return $next($request);
    }
}
