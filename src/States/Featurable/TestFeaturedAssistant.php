<?php

namespace Thinktomorrow\Chief\States\Featurable;

use Illuminate\Http\Request;

class TestFeaturedAssistant
{
    // define routes and their endpoints
    // default AssistantController guides to the Assistant to convention method like managers:
    // e.g. Route::post('pages/archive', [AssistantController, 'archive']); -> archive method is propagated to
    // ArchiveAssistant@archive

    public static function register($app)
    {
         Route::get('testje', [static::class, 'archive']);
         Route::get('testje', [AssistantController, 'archive']);

         // fetched manager
        // propagate naar assistant::archive
    }

    public function archive(Request $request)
    {
        // Get manager

        $this->model->archive();
    }
}
