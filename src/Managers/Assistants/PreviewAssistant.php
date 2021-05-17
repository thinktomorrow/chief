<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Managers\Assistants;

use Thinktomorrow\Chief\Site\Visitable\Visitable;

trait PreviewAssistant
{
    public function canPreviewAssistant(string $action, $model = null): bool
    {
        if ($action !== 'preview' || ! $model) {
            return false;
        }

        return $model instanceof Visitable;
    }

    /**
     * Return the fully qualified route for a given action.
     *
     * When dealing with a dynamic route constructed from a specific model,
     * a model instance should be passed as second argument.
     *
     * @param string $action
     * @param null $model
     * @param array $parameters
     * @return null|string
     */
    public function routePreviewAssistant(string $action, $model = null, ...$parameters): ?string
    {
        if (! $this->canPreviewAssistant($action, $model)) {
            return null;
        }

        return $model->url();
    }
}
