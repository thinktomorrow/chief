<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\App\Actions;

use Illuminate\Database\Eloquent\Relations\Relation;
use Thinktomorrow\Chief\Forms\Forms;
use Thinktomorrow\Chief\Fragments\Domain\Models\FragmentModel;
use Thinktomorrow\Chief\Fragments\Domain\Models\FragmentRepository;

final class CreateFragment
{
    private FragmentRepository $fragmentRepository;

    public function __construct(FragmentRepository $fragmentRepository)
    {
        $this->fragmentRepository = $fragmentRepository;
    }

    public function handle(string $fragmentKey, array $input, array $files = []): string
    {
        $fragment = app(Relation::getMorphedModel($fragmentKey));

        $fields = Forms::make($fragment->fields($fragment))
            ->fillModel($fragment->fragmentModel())
            ->getFields()
            ->notTagged(['edit', 'not-on-create']);

        $fragmentModel = FragmentModel::create([
            'id' => $this->fragmentRepository->nextId(),
            'key' => $fragmentKey,
        ]);

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