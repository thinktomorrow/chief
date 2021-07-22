<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\Actions;

use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Chief\Fragments\Database\ContextModel;

final class DeleteContext
{
    private DeleteFragment $deleteFragment;
    private DetachSharedFragment $detachSharedFragment;

    public function __construct(DeleteFragment $deleteFragment, DetachSharedFragment $detachSharedFragment)
    {
        $this->deleteFragment = $deleteFragment;
        $this->detachSharedFragment = $detachSharedFragment;
    }

    public function handle(Model $owner): void
    {
        if (! $context = ContextModel::ownedBy($owner)) {
            return;
        }

        foreach ($context->fragments()->get() as $fragmentModel) {
            if ($fragmentModel->isShared()) {
                $this->detachSharedFragment->handle($owner, $fragmentModel);
            } else {
                $this->deleteFragment->handle($fragmentModel->id);
            }
        }

        $context->delete();
    }
}
