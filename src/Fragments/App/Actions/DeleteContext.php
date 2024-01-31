<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\App\Actions;

use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Chief\Fragments\Domain\Models\ContextModel;
use Thinktomorrow\Chief\Fragments\Domain\Models\ContextRepository;

final class DeleteContext
{
    private ContextRepository $contextRepository;
    private DetachFragment $detachFragment;

    public function __construct(ContextRepository $contextRepository, DetachFragment $detachFragment)
    {
        $this->contextRepository = $contextRepository;
        $this->detachFragment = $detachFragment;
    }

    public function handle(string $contextId): void
    {
        $context = $this->contextRepository->find($contextId);

        foreach ($context->fragments()->get() as $fragmentModel) {
            $this->detachFragment->handle($contextId, $fragmentModel->id);
        }

        $context->delete();
    }
}
