<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\App\Actions;

use Thinktomorrow\Chief\Forms\Forms;
use Thinktomorrow\Chief\Fragments\Models\FragmentModel;
use Thinktomorrow\Chief\Fragments\Repositories\FragmentFactory;
use Thinktomorrow\Chief\Fragments\Repositories\FragmentRepository;

final class CreateFragment
{
    private FragmentRepository $fragmentRepository;

    public function __construct(FragmentRepository $fragmentRepository)
    {
        $this->fragmentRepository = $fragmentRepository;
    }

    public function handle(string $fragmentKey, array $input, array $files = []): string
    {
        $fragmentModel = FragmentModel::create([
            'id' => $this->fragmentRepository->nextId(),
            'key' => $fragmentKey,
        ]);

        $fragment = app(FragmentFactory::class)->create($fragmentModel);

        $fields = Forms::make($fragment->fields($fragment))
            ->fillModel($fragment->getFragmentModel())
            ->getFields()
            ->notTagged(['edit', 'not-on-create']);

        // Save Fragment values
        app($fragment->getSaveFieldsClass())->save(
            $fragmentModel,
            $fields,  // Fields::make($fragment->fields($fragment))->notTagged('edit'),
            $input,
            $files
        );

        return $fragmentModel->id;
    }
}
