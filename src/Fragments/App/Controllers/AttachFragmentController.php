<?php

namespace Thinktomorrow\Chief\Fragments\App\Controllers;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\Fragments\App\Actions\AttachFragment;
use Thinktomorrow\Chief\Fragments\Domain\Exceptions\FragmentAlreadyAdded;

class AttachFragmentController
{
    public function attach(string $contextId, string $fragmentId, Request $request)
    {
        try {
            app(AttachFragment::class)->handle($contextId, $fragmentId, $request->input('order', 0));
        } catch (FragmentAlreadyAdded $e) {
            return response()->json([
                'message' => 'fragment [' . $fragmentId . '] is already added',
                'data' => [],
            ], 400);
        }

        return response()->json([
            'message' => 'fragment [' . $fragmentId . '] added',
            'data' => [],
        ], 201);
    }
}
