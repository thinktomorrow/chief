<?php

namespace Thinktomorrow\Chief\Plugins\Hive\App\Controllers;

use Thinktomorrow\Chief\App\Http\Controllers\Controller;

class HiveController extends Controller
{
    public function __construct() {}

    public function suggest()
    {
        if ($promptClass = request()->input('prompt')) {
            if (! $promptClass || ! class_exists($promptClass)) {
                return response()->json(['error' => 'Invalid prompt class'], 400);
            }

            $result = app($promptClass)->prompt(request()->input('payload', []));

            // TEMP
            $result = $result->getAltTexts()['nl'];

            return response()->json(['suggestions' => $result]);
        }

        return response()->json([
            'suggestions' => [
                'example suggestion 1',
                'example suggestion 2',
                'example suggestion 3',
            ],
        ]);
    }
}
