<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\App\Http\Controllers\Controller;
use Thinktomorrow\Chief\Management\Application\SortModels;

class SortController extends Controller
{
    /** @var SortModels */
    private $sortModels;

    public function __construct(SortModels $sortModels)
    {
        $this->sortModels = $sortModels;
    }

    public function sort(Request $request)
    {
        if(!$request->modelType || !$request->indices) {
            throw new \InvalidArgumentException('Missing arguments [modelType] or [indices] for sorting request.');
        }

        $this->sortModels->handle($request->modelType, $request->indices);

        return response()->json([
            'message' => 'models sorted.'
        ], 200);
    }
}
