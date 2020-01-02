<?php

namespace Thinktomorrow\Chief\Tests\Feature\Assistants\Stubs;

use Illuminate\Support\Facades\Route;
use Thinktomorrow\Chief\Management\Manager;
use Thinktomorrow\Chief\Management\Assistants\Assistant;
use Thinktomorrow\Chief\App\Http\Controllers\Back\Assistants\AssistantController;

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
}
