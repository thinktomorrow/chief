<?php

namespace Thinktomorrow\Chief\Fragments\App\Actions;

use Thinktomorrow\Chief\Forms\Fields\Validation\FieldValidator;
use Thinktomorrow\Chief\Forms\Layouts\Layout;
use Thinktomorrow\Chief\Fragments\App\Repositories\FragmentRepository;
use Thinktomorrow\Chief\Fragments\Events\FragmentUpdated;
use Thinktomorrow\Chief\Fragments\UI\Livewire\_partials\WithNullifyEmptyValues;

class UpdateFragment
{
    use WithNullifyEmptyValues;

    private FragmentRepository $repository;

    private FieldValidator $validator;

    public function __construct(FragmentRepository $repository, FieldValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    public function handle(string $contextId, string $fragmentId, array $locales, array $data, array $files)
    {
        $fragment = $this->repository->find($fragmentId);

        /**
         * Nullify empty string values so that they are stored as null in the database and
         * not as empty strings. This is important for the fallback locale mechanism.
         */
        $data = $this->nullifyEmptyValues($data);

        $fields = Layout::make($fragment->fields($fragment))
            ->model($fragment->getFragmentModel())
            ->setLocales($locales)
            ->getFields();

        $this->validator->handle($fields, $data);

        // Save Fragment values
        app($fragment->getSaveFieldsClass())->save(
            $fragment->getFragmentModel(),
            $fields,
            $data,
            $files
        );

        event(new FragmentUpdated($fragment->getFragmentId(), $contextId));
    }
}
