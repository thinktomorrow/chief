<?php

namespace Thinktomorrow\Chief\Fragments\Controllers;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\Fragments\App\Actions\AttachFragment;
use Thinktomorrow\Chief\Fragments\Exceptions\FragmentAlreadyAdded;

class AttachFragmentController
{
    public function attach(string $contextId, string $fragmentId, ?string $parentId, Request $request)
    {
        try {
            app(AttachFragment::class)->handle($contextId, $fragmentId, $parentId, $request->input('order', 0));
        } catch (FragmentAlreadyAdded $e) {
            return response()->json([
                'message' => 'fragment ['.$fragmentId.'] is already added',
                'data' => [],
            ], 400);
        }

        return response()->json([
            'message' => 'fragment ['.$fragmentId.'] added',
            'data' => [],
        ], 201);
    }

    public function attachRoot(string $contextId, string $fragmentId, Request $request)
    {
        return $this->attach($contextId, $fragmentId, null, $request);
    }
}
