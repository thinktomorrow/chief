<?php

namespace Thinktomorrow\Chief\Fragments\App\Controllers;

use Thinktomorrow\Chief\Fragments\App\Actions\DetachFragment;
use Thinktomorrow\Chief\Fragments\Resource\Exceptions\FragmentAlreadyDetached;

class DetachOrDeleteFragmentController
{
    public function delete(string $contextId, string $fragmentId)
    {
        try {
            // This detaches the fragment from given context - if the fragment is not shared / used
            // elsewhere it will be deleted completely via listener on the FragmentDetached event
            app(DetachFragment::class)->handle($contextId, $fragmentId);
        } catch (FragmentAlreadyDetached $e) {
            return response()->json([
                'message' => 'fragment [' . $fragmentId . '] is already removed.',
                'data' => [],
            ], 400);
        }

        return response()->json([
            'message' => 'fragment [' . $fragmentId . '] detached',
            'data' => [],
        ]);
    }
}
