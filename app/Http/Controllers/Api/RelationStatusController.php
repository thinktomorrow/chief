<?php

namespace Thinktomorrow\Chief\App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\App\Http\Controllers\Controller;
use Thinktomorrow\Chief\Relations\Relation;

class RelationStatusController extends Controller
{
    public function update(Request $request)
    {
        $relation = Relation::find($request->parent_type, $request->parent_id, $request->child_type, $request->child_id);

        if (! $relation) {
            throw new \DomainException('No relation found by passed api arguments. ' . var_export($request->all(), true));
        }

        $relation->online_status = ! ! $request->online_status;
        $relation->save();

        return response()->json([
            'message' => 'relation status updated.',
        ], 200);
    }
}
