<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\Actions;

use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Chief\Fragments\Database\FragmentModel;
use Thinktomorrow\Chief\Fragments\Database\FragmentRepository;
use Thinktomorrow\Chief\Fragments\Fragmentable;

final class CreateFragmentModel
{
    /** @var FragmentRepository */
    private FragmentRepository $fragmentRepository;

    /** @var AddFragmentModel */
    private AddFragmentModel $addFragmentToContext;

    public function __construct(FragmentRepository $fragmentRepository, AddFragmentModel $addFragmentToContext)
    {
        $this->fragmentRepository = $fragmentRepository;
        $this->addFragmentToContext = $addFragmentToContext;
    }

    /**
     * Store a non-static fragmentable.
     *
     * @param Model $owner
     * @param Fragmentable $fragmentable
     * @param int $order
     * @param array $data
     */
    public function create(Model $owner, Fragmentable $fragmentable, int $order, array $data = []): FragmentModel
    {
        $fragmentModel = FragmentModel::create([
            'id' => $this->fragmentRepository->nextId(),
            'model_reference' => $fragmentable->modelReference()->get(),
            'data' => $data,
        ]);

        $this->addFragmentToContext->handle($owner, $fragmentModel, $order);

        return $fragmentModel;
    }
}
