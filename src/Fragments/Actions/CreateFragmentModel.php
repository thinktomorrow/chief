<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\Actions;

use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Chief\Fragments\Database\FragmentModel;
use Thinktomorrow\Chief\Fragments\Database\FragmentRepository;
use Thinktomorrow\Chief\Fragments\Fragmentable;

final class CreateFragmentModel
{
    private FragmentRepository $fragmentRepository;

    private AddFragmentModel $addFragmentToContext;

    public function __construct(FragmentRepository $fragmentRepository, AddFragmentModel $addFragmentToContext)
    {
        $this->fragmentRepository = $fragmentRepository;
        $this->addFragmentToContext = $addFragmentToContext;
    }

    /**
     * Store a non-static fragmentable.
     */
    public function create(Model $owner, Fragmentable $fragmentable, int $order, array $data = []): FragmentModel
    {
        $fragmentModel = FragmentModel::create([
            'id' => $this->fragmentRepository->nextId(),
            'model_reference' => $fragmentable->modelReference()->getShort(),
            'data' => $data,
        ]);

        $this->addFragmentToContext->handle($owner, $fragmentModel, $order);

        return $fragmentModel;
    }
}
