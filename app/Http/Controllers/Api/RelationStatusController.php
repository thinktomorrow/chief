<?php

namespace Thinktomorrow\Chief\App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Thinktomorrow\Chief\Relations\Relation;
use Thinktomorrow\Chief\App\Http\Controllers\Controller;
use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Urls\ProvidesUrl\ProvidesUrl;
use Thinktomorrow\Chief\Urls\UrlHelper;

class RelationStatusController extends Controller
{
    public function update(Request $request)
    {
        $relation = Relation::query()
            ->where('parent_type', $request->parent_type )
            ->where('parent_id', $request->parent_id )
            ->where('child_type', $request->child_type )
            ->where('child_id', $request->child_id )
            ->first();

        if(!$relation) {
            throw new \DomainException('No relation found by passed api arguments. ' . var_export($request->all(), true));
        }

        $relation->online_status = !! $request->online_status;
        $relation->save();

        return response()->json([
            'message' => 'relation status updated.'
        ], 200);
    }
}
