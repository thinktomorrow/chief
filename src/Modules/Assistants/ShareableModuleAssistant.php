<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Modules\Assistants;

use Illuminate\Support\Collection;

trait ShareableModuleAssistant
{
    // Used by the ModulesController::index
    public static function allForLegacyIndex(): Collection
    {
        return $this->managedModelClass()::all();
    }
}
