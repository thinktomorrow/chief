<?php

namespace Thinktomorrow\Chief\Fragments\App\ContextActions;

use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;

class SaveContexts
{
    private ContextApplication $contextApplication;

    public function __construct(ContextApplication $contextApplication)
    {
        $this->contextApplication = $contextApplication;
    }

    public function handle(ModelReference $modelReference, array $contextValues): void
    {
        foreach ($contextValues as $contextId => $values) {

            // TODO: restrict this to prevent deletion of default context
            if (is_null($values)) {
                $this->contextApplication->delete(new DeleteContext($contextId));

                continue;
            }

            if (str_starts_with($contextId, 'new-')) {
                $this->contextApplication->create(new CreateContext($modelReference, $values['locales'], $values['title']));

                continue;
            }

            $this->contextApplication->update(new UpdateContext($contextId, $values['locales'], $values['title']));
        }
    }
}
