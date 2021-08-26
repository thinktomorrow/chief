<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\Actions;

use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Chief\Fragments\Database\ContextModel;

final class DeleteContext
{
    private DetachFragment $detachFragment;

    public function __construct(DetachFragment $detachFragment)
    {
        $this->detachFragment = $detachFragment;
    }

    public function handle(Model $owner): void
    {
        if (! $context = ContextModel::ownedBy($owner)) {
            return;
        }

        foreach ($context->fragments()->get() as $fragmentModel) {
            $this->detachFragment->handle($owner, $fragmentModel);
        }

        $context->delete();
    }
}
