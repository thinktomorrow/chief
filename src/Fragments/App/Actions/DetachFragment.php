<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\App\Actions;

use Thinktomorrow\Chief\Fragments\Domain\Events\FragmentDetached;
use Thinktomorrow\Chief\Fragments\Domain\Exceptions\FragmentAlreadyDetached;
use Thinktomorrow\Chief\Fragments\Domain\Models\ContextModel;

class DetachFragment
{
    /**
     * Detach a fragment from a given context.
     * Keep the fragment model itself intact
     */
    public function handle(string $contextId, string $fragmentId): void
    {
        $context = ContextModel::findOrFail($contextId);

        if (! $context->fragments()->where('id', $fragmentId)->exists()) {
            throw new FragmentAlreadyDetached('Fragment [' . $fragmentId . '] already detached or does not exist in context [' . $contextId . ']');
        }

        $context->fragments()->detach($fragmentId);

        event(new FragmentDetached($fragmentId, $context->id));
    }
}
