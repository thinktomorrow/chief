<?php

namespace Thinktomorrow\Chief\Management\Application;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\Common\Audit\Audit;
use Thinktomorrow\Chief\Management\ModelManager;
use Thinktomorrow\Chief\Management\Exceptions\DeleteAborted;

class DeleteManager
{
    public function handle(ModelManager $manager, Request $request)
    {
        if($request->get('deleteconfirmation') != 'DELETE')
        {
            throw new DeleteAborted();
        }
        $manager->delete();
    }
}
