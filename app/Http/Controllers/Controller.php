<?php

namespace Thinktomorrow\Chief\App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Collection;

class Controller extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    protected function lengthAwarePaginator(Collection $collection, $itemsPerPage, $pageName)
    {
        $paginator = new LengthAwarePaginator(
            $collection->forPage(Paginator::resolveCurrentPage($pageName), $itemsPerPage),
            $collection->count(),
            $itemsPerPage,
            Paginator::resolveCurrentPage($pageName),
            [
                'path' => Paginator::resolveCurrentPath($pageName),
                'pageName' => $pageName,
            ]
        );

        return $paginator;
    }
}
