<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\App\Actions;

use Thinktomorrow\Chief\Forms\Fields\Validation\FieldValidator;
use Thinktomorrow\Chief\Forms\Layouts\PageLayout;
use Thinktomorrow\Chief\Fragments\App\Repositories\FragmentFactory;
use Thinktomorrow\Chief\Fragments\App\Repositories\FragmentRepository;
use Thinktomorrow\Chief\Fragments\Models\FragmentModel;

final class CreateFragment
{
    private FragmentRepository $fragmentRepository;

    private FieldValidator $validator;

    public function __construct(FragmentRepository $fragmentRepository, FieldValidator $validator)
    {
        $this->fragmentRepository = $fragmentRepository;
        $this->validator = $validator;
    }

    public function handle(string $fragmentKey, array $locales, array $input, array $files = []): string
    {
        $fragmentModel = FragmentModel::create([
            'id' => $this->fragmentRepository->nextId(),
            'key' => $fragmentKey,
        ]);

        $fragment = app(FragmentFactory::class)->create($fragmentModel);

        $fields = PageLayout::make($fragment->fields($fragment))
            ->model($fragment->getFragmentModel())
            ->setLocales($locales)
            ->getFields()
            ->filterByNotTagged(['edit', 'not-on-create']);

        $this->validator->handle($fields, $input);

        // Save Fragment values
        app($fragment->getSaveFieldsClass())->save(
            $fragmentModel,
            $fields,  // Fields::make($fragment->fields($fragment))->filterByNotTagged('edit'),
            $input,
            $files
        );

        return $fragmentModel->id;
    }
}
