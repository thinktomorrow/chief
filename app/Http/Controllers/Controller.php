<?php

namespace Thinktomorrow\Chief\App\Http\Controllers;

use Illuminate\Support\Collection;
use Illuminate\Pagination\Paginator;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function lengthAwarePaginator(Collection $collection, $itemsPerPage, $pageName)
    {
        $paginator = new LengthAwarePaginator(
            $collection->forPage(Paginator::resolveCurrentPage($pageName), $itemsPerPage),
            $collection->count(),
            $itemsPerPage,
            Paginator::resolveCurrentPage($pageName),
            [
                'path'     => Paginator::resolveCurrentPath($pageName),
                'pageName' => $pageName
            ]);

        return $paginator;
    }
}
