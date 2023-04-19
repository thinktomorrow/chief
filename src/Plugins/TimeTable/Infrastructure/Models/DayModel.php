<?php

namespace Thinktomorrow\Chief\Plugins\TimeTable\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Thinktomorrow\Chief\Forms\Fields\MultiSelect;
use Thinktomorrow\Chief\Forms\Fields\Repeat;
use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Forms\Layouts\Grid;
use Thinktomorrow\Chief\Plugins\TimeTable\Application\Read\TimeTableReadRepository;
use Thinktomorrow\Chief\Resource\PageResource;
use Thinktomorrow\Chief\Resource\PageResourceDefault;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModel;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModelDefault;

class DayModel extends Model implements ReferableModel, PageResource
{
    use ReferableModelDefault;
    use PageResourceDefault;

    protected $guarded = [];
    public $table = 'timetables_days';
    public $timestamps = false;
    public $casts = [
        'slots' => 'array',
    ];

    public function getIndexTitle(): string
    {
        return 'Dag';
    }

    public function getLabel(): string
    {
        return 'date';
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
        yield MultiSelect::make('timetable_id')
            ->label('Tijdschema')
            ->options(fn() => app(TimeTableReadRepository::class)->getAllTimeTablesForSelect());

        yield MultiSelect::make('weekday')
            ->label('Dag van de week')
            ->options([
                'maandag',
                'dindag',
                'woensdag',
                'donderdag',
                'vrijdag',
                'zaterdag',
                'zondag',
            ]);

        yield Repeat::make('hours')->items([
            Grid::make()->columns(2)->items([
                Text::make('from'),
                Text::make('until'),
            ]),
        ]);

        yield Text::make('content')
            ->label('Eigen tekst')
            ->locales()
            ->description('Zet het in korte mensentaal. Bijv. "Gesloten op Paasmaandag", "Kantoor dicht want Teambuilding 🥳"');
    }

    public function timetable(): BelongsTo
    {
        return $this->belongsTo(app(TimeTableModel::class), 'timetable_id');
    }
}
