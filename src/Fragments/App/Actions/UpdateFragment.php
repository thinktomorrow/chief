<?php

namespace Thinktomorrow\Chief\Fragments\App\Actions;

use Thinktomorrow\Chief\Forms\Fields\Validation\FieldValidator;
use Thinktomorrow\Chief\Forms\Layouts\Layout;
use Thinktomorrow\Chief\Fragments\App\Repositories\FragmentRepository;
use Thinktomorrow\Chief\Fragments\Events\FragmentUpdated;

class UpdateFragment
{
    private FragmentRepository $repository;

    private FieldValidator $validator;

    public function __construct(FragmentRepository $repository, FieldValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    public function handle(string $contextId, string $fragmentId, array $data, array $files)
    {
        $fragment = $this->repository->find($fragmentId);

        $forms = Layout::make($fragment->fields($fragment))
            ->model($fragment->getFragmentModel());

        $this->validator->handle($forms->getFields(), $data);

        // Save Fragment values
        app($fragment->getSaveFieldsClass())->save(
            $fragment->getFragmentModel(),
            $forms->getFields(),
            $data,
            $files
        );

        event(new FragmentUpdated($fragment->getFragmentId(), $contextId));
    }
}
