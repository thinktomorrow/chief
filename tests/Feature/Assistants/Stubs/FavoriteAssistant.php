<?php

namespace Thinktomorrow\Chief\Tests\Feature\Assistants\Stubs;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\Management\Manager;
use Thinktomorrow\Chief\Management\Assistants\Assistant;

class FavoriteAssistant implements Assistant
{
    private $manager;

    public function manager(Manager $manager)
    {
        $this->manager = $manager;
    }

    public static function key(): string
    {
        return 'favorite';
    }

    public function route($verb): ?string
    {
        if($verb == 'favorize') return route('dummy.favorite', [
            $this->key(),
            'favorize',
            $this->manager->managerKey(),
            $this->manager->existingModel()->id
        ]);

        return null;
    }

    public function can($verb): bool
    {
        return true;
    }

    public function favorize(Request $request)
    {
        $this->manager->existingModel()->favorite = true;
    }
}
