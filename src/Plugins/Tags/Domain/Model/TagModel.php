<?php

namespace Thinktomorrow\Chief\Plugins\Tags\Domain\Model;

use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Chief\Forms\Fields\Common\FieldPresets;
use Thinktomorrow\Chief\Forms\Fields\MultiSelect;
use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Forms\Form;
use Thinktomorrow\Chief\Plugins\Tags\Application\Read\TagReadRepository;
use Thinktomorrow\Chief\Resource\PageResource;
use Thinktomorrow\Chief\Resource\PageResourceDefault;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModel;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModelDefault;

class TagModel extends Model implements ReferableModel, PageResource
{
    use ReferableModelDefault;
    use PageResourceDefault;

    protected $guarded = [];
    public $table = 'chief_tags';
    public $timestamps = false;

    public function getIndexTitle(): string
    {
        return 'Tags';
    }

    public function getLabel(): string
    {
        return 'tag';
    }

    protected function getNavIcon(): ?string
    {
        return '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                </svg>';
    }

    public function getNavTags(): array
    {
        return ['nav-catalog'];
    }

    public function fields($model): iterable
    {
        yield FieldPresets::pagetitle(
            Text::make('label')
            ->label('Label tekst')
            ->description('Korte labels duren het langst.')
            ->required()
            ->characterCount('20')
            ->rules('max:20')
        );

        yield Text::make('color')->label('Label kleur')
            ->description('Geef de tag een eigen kleur. Liefst een geldige hex waarde.')
            ->placeholder('#6366F1');

        $tagGroupsForSelect = app(TagReadRepository::class)->getAllGroupsForSelect();

        // If categories are empty / unused - we omit the field
        if (count($tagGroupsForSelect) > 0) {
            yield Form::make('taxon_form')
                ->title('Groep')
                ->items([
                    MultiSelect::make('taggroup_id')
                        ->label('Groep')
                        ->description('Zet deze tag in een vakje. Net iets overzichtelijker.')
                        ->options($tagGroupsForSelect),
                ]);
        }
    }

    public function taggroups(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(app(TagGroupModel::class), 'taggroup_id');
    }
}
