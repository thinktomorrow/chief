<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\Actions;

use Thinktomorrow\Chief\Fragments\Fragmentable;
use Thinktomorrow\Chief\Fragments\Database\ContextModel;
use Thinktomorrow\Chief\Fragments\FragmentsOwner;
use Thinktomorrow\Chief\Fragments\Database\FragmentModel;
use Thinktomorrow\Chief\Fragments\Database\FragmentRepository;

final class CreateFragmentModel
{
    /** @var FragmentRepository */
    private FragmentRepository $fragmentRepository;

    public function __construct(FragmentRepository $fragmentRepository)
    {
        $this->fragmentRepository = $fragmentRepository;
    }

    /**
     * Store a non-static fragmentable.
     *
     * @param FragmentsOwner $owner
     * @param Fragmentable $fragmentable
     * @param int $order
     * @param array $data
     */
    public function create(FragmentsOwner $owner, Fragmentable $fragmentable, int $order, array $data = []): FragmentModel
    {
        if(!$context = ContextModel::ownedBy($owner)) {
            $context = ContextModel::createForOwner($owner);
        }

        return FragmentModel::create([
            'id'              => $this->fragmentRepository->nextId(),
            'context_id'      => $context->id,
            'model_reference' => $fragmentable->modelReference()->get(),
            'data'            => $data,
            'order'           => $order,
        ]);
    }
}
