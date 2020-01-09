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
        if($verb == 'dummy-favorite') return route('dummy.favorite', [
            $this->manager->details()->key,
            $this->manager->model()->id,
            $this->key(),
        ]);
    }

    public function can($verb): bool
    {
        return true;
    }

    public function favorize(Request $request)
    {
        $this->manager->model()->favorite = true;
    }
}
